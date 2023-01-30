<?php

namespace App\Command;

use App\Repository\SerieRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(
    name: 'app:update-results',
    description: 'Hydrate bdd with matches results'
)]
class UpdateResultsCommand extends Command
{
    public function __construct(
        private readonly SerieRepository $serieRepository
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
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $onGoingSeries = $this->serieRepository->findBy(['ended' => false]);
        $application = $this->getApplication();

        if (null === $application) {
            $output->writeln('Missing application. abort.');

            return Command::FAILURE;
        }

        $command = $application->find('app:panda-score');

        foreach ($onGoingSeries as $serie) {
            $arguments = [
                'id'    => $serie->getId(),
                '--publish-hub'  => true,
            ];

            $greetInput = new ArrayInput($arguments);
            $command->run($greetInput, $output);
        }

        $output->writeln('Results updated!');

        return Command::SUCCESS;
    }
}