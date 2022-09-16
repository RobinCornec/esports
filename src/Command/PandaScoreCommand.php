<?php

namespace App\Command;

use App\Entity\Matches;
use App\Repository\MatchesRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:panda-score')]
class PandaScoreCommand extends Command
{
    private const VIDEOGAMES_URI = '/videogames';
    private const MATCH_ID = '645990';
    private const MATCH_URI = '/matches/' . self::MATCH_ID;

    public function __construct(
        private readonly HttpClientInterface $pandascoreClient,
        private readonly MatchesRepository $matchesRepository,
        private readonly ManagerRegistry $doctrine,
        private readonly HubInterface $hub,
        private readonly Environment $twig
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('This command allows you display list of available games')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->pandascoreClient->request(
            'GET',
            self::MATCH_URI,
        );

        $content = json_decode($response->getContent());

        $match = $this->matchesRepository->findOneBy(['pandaScoreId' => self::MATCH_ID]);

        if (null === $match) {
            $match = new Matches();
        }

        $match->setName($content->name);
        $match->setTeam1Id($content->opponents[0]->opponent->id);
        $match->setTeam2Id($content->opponents[1]->opponent->id);
        $match->setTeam1Name($content->opponents[0]->opponent->name);
        $match->setTeam2Name($content->opponents[1]->opponent->name);
        $match->setTeam1Score($content->opponents[0]->opponent->id === $content->results[0]->team_id ? $content->results[0]->score : $content->results[1]->score);
        $match->setTeam2Score($content->opponents[1]->opponent->id === $content->results[1]->team_id ? $content->results[1]->score : $content->results[0]->score);
        $match->setEnded(null !== $content->winner);
        $match->setPandaScoreId(self::MATCH_ID);

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($match);
        $entityManager->flush();

        $update = new Update(
            'user-1',
            $this->twig->render('match/message.stream.html.twig', ['match' => $match])
        );

        $this->hub->publish($update);

        $output->writeln('Pushed to mercure');
        $output->writeln('Done!');

        return Command::SUCCESS;
    }
}