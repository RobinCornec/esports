<?php

namespace App\Command;

use App\Entity\Contest;
use App\Entity\Series;
use App\Entity\Tournament;
use App\Repository\ContestRepository;
use App\Repository\SerieRepository;
use App\Repository\TournamentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use JsonException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

// the name of the command is what users type after "php bin/console"
#[AsCommand(
    name: 'app:panda-score',
    description: 'Hydrate bdd with new serie'
)]
class PandaScoreCommand extends Command
{
    private const SERIES_URI = '/series/';
    private const MATCHES_URI = '/matches';

    public function __construct(
        private readonly Environment $twig,
        private readonly HubInterface $hub,
        private readonly ManagerRegistry $doctrine,
        private readonly ContestRepository $matchesRepository,
        private readonly SerieRepository $seriesRepository,
        private readonly TournamentRepository $tournamentsRepository
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('This command allows you display list of available games')
            ->addArgument('id', InputArgument::REQUIRED, 'The panda score id of the serie')
            ->addOption('publish-hub', 'ph', InputOption::VALUE_NONE, 'Publish result to mercure hub')
        ;
    }


    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws ClientExceptionInterface
     * @throws JsonException
     * @throws LoaderError
     * @throws RedirectionExceptionInterface
     * @throws RuntimeError
     * @throws ServerExceptionInterface
     * @throws SyntaxError
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $argumentSerieId = $input->getArgument('id');

        $responseSerie = $this->pandascoreClient->request(
            'GET',
            self::SERIES_URI . $argumentSerieId,
        );

        $responseContests = $this->pandascoreClient->request(
            'GET',
            self::SERIES_URI . $argumentSerieId . self::MATCHES_URI,
        );


        $apiSerie = json_decode($responseSerie->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $apiContests = json_decode($responseContests->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $findedSerie = $this->seriesRepository->findOneBy(['pandaScoreId' => $argumentSerieId]);
        $entitySerie = (new Series())->create($apiSerie, $argumentSerieId, $findedSerie);
        $this->seriesRepository->add($entitySerie);

        $listEntitiesTournaments = [];
        $updateAll = false;
        $listNotEndedContests = [];
        $listContests = [];

        if ($input->getOption('publish-hub')) {
            $listNotEndedContests = $this->matchesRepository->findBy(['ended' => false]);
        }

        foreach ($apiSerie['tournaments'] as $apiTournament) {
            $findedTournament = $this->tournamentsRepository->findOneBy(['pandaScoreId' => $apiTournament['id']]);
            $entityTournament = (new Tournament())->create($apiTournament, $apiTournament['id'], $entitySerie, $findedTournament);
            $this->tournamentsRepository->add($entityTournament);
            $listEntitiesTournaments[$entityTournament->getPandaScoreId()] = $entityTournament;
            $entityTournament= null;
        }

        foreach ($apiContests as $apiContest) {
            $findedContest = $this->matchesRepository->findOneBy(['pandaScoreId' => $apiContest['id']]);

            if (null === $findedContest) {
                $updateAll = true;
            }

            $findedTournament = $listEntitiesTournaments[$apiContest['tournament_id']];
            $entityContest = (new Contest())->create($apiContest, $apiContest['id'], $findedTournament, $findedContest);

            $this->matchesRepository->add($entityContest);
            $listContests[$entityContest->getPandaScoreId()] = $entityContest;

            $entityContest = null;
        }

        $this->doctrine->getManager()->flush();

        if ($input->getOption('publish-hub')) {
            $file = 'single.stream.html.twig';
            foreach ($listNotEndedContests as $notEndedContest) {
                $listContests[] = $listContests[$notEndedContest->getPandaScoreId()];
            }

            if ($updateAll) {
                $file = 'all.stream.html.twig';
                $listContests = $this->matchesRepository->findBy([], [
                    'ended' => 'asc',
                    'beginAt' => 'desc',
                ]);
            }

            $update = new Update(
                'games-lol',
                $this->twig->render('matches/' . $file, ['matches' => $listContests])
            );

            $this->hub->publish($update);

            $output->writeln('Mercure updated!');
        }

        $output->writeln('Series hydrated!');

        return Command::SUCCESS;
    }
}