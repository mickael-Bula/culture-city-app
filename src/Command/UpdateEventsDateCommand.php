<?php

namespace App\Command;

use DateTime;
use DateInterval;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateEventsDateCommand extends Command
{
    protected static $defaultName = 'u:event:date';
    protected static $defaultDescription = 'Mise à jour des dates des events + 7 jours';

    private $eventRepository;
    private $entityManagerInterface;

    public function __construct(EventRepository $eventRepository, EntityManagerInterface $entityManagerInterface)
    {
        $this->eventRepository = $eventRepository; 
        $this->entityManagerInterface = $entityManagerInterface;   

        parent::__construct();
    }

    protected function configure(): void
    {
        //todo ici l'automatiser avec des tâche cron pour qu'elle s'exécute toute seule.
        $this->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

     $io = new SymfonyStyle($input, $output);

                $io->info(sprintf('On démarre'));

                $events = $this->eventRepository->findAll();

                foreach ($events as $event) {

                    //$io->info('Date actuele de l\'événement : ' . $actualEventDateInDataBase );

                    // set datetime on now and 21HOO
                    $actualEventDate = new DateTime('21:00');
                    //get actuel event date
                    $event->getStartDate();
                    // add rendom interval between + 5 to 7 day
                    $NewEventDate = $actualEventDate->add(new DateInterval('P' . rand(5,7). 'D'));
                    // set the new events date on day + 5 to 7
                    $event->setStartDate($NewEventDate);
                    $io->info('Résultat : ' . $event->setStartDate($NewEventDate));
                }

                // enregistrer la nouvelle date
                $this->entityManagerInterface->flush();

                //message de sortie
                $io->success(sprintf('Terminé'));
                return Command::SUCCESS;    

    }
}
