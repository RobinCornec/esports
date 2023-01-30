<?php

namespace App\Command;

use App\Entity\League;
use App\Entity\Videogame;
use App\Repository\LeagueRepository;
use App\Repository\VideogameRepository;
use Doctrine\Persistence\ManagerRegistry;
use JsonException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

// the name of the command is what users type after "php bin/console"

#[AsCommand(
    name: 'app:hydrate-games',
    description: 'Hydrate bdd with games'
)]
class HydrateGamesCommand extends Command
{
    private const VIDEOGAME_URI = '/videogames';

    public function __construct(
        private readonly HttpClientInterface $pandascoreClient,
        private readonly LeagueRepository $leagueRepository,
        private readonly ManagerRegistry $doctrine,
        private readonly VideogameRepository $videogameRepository,
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
     * @throws JsonException
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $responseVideogames = $this->pandascoreClient->request(
            'GET',
            self::VIDEOGAME_URI,
        );

        $apiVideogame = json_decode($responseVideogames->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $dbVideogames = $this->videogameRepository->findAll();
        $refactoDbVideogames = [];

        foreach ($dbVideogames as $dbVideoGame) {
            $refactoDbVideogames[$dbVideoGame->getId()] = $dbVideoGame;
        }
        foreach ($apiVideogame as $videogame) {
            $entityVideoGame = new Videogame();
            $entityVideoGame->setId($videogame['id']);

            if (array_key_exists($videogame['id'], $refactoDbVideogames)) {
                $entityVideoGame = $refactoDbVideogames[$videogame['id']];
            }

            $entityVideoGame->setName($videogame['name']);
            $entityVideoGame->setSlug($videogame['slug']);
            $this->videogameRepository->add($entityVideoGame);

            $dbLeagues = $entityVideoGame->getLeagues();
            $refactoDbLeague = [];

            if ($dbLeagues !== null) {
                foreach ($dbLeagues as $dbLeague) {
                    $refactoDbLeague[$dbLeague->getId()] = $dbLeague;
                }
            }
            
            foreach ($videogame['leagues'] as $league) {
                $entityLeague = new League();
                $entityLeague->setId($league['id']);

                if (array_key_exists($league['id'], $refactoDbLeague)) {
                    $entityLeague = $refactoDbLeague[$league['id']];
                }

                $entityLeague->setName($league['name']);
                $entityLeague->setSlug($league['slug']);
                $entityLeague->setImage($league['image_url']);
                $entityLeague->setUrl($league['url']);
                $entityLeague->setVideogame($entityVideoGame);

                $this->leagueRepository->add($entityLeague);

                $entityLeague = null;
            }

            $entityVideoGame = null;
        }

        $this->doctrine->getManager()->flush();

        return Command::SUCCESS;
    }
}