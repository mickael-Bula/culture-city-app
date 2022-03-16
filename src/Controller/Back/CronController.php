<?php

namespace App\Controller\Back;

use DateTime;
use DateInterval;
//use App\Entity\Event;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
//use Symfony\Component\Console\Command\Command;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CronController extends AbstractController

{

/**
 * @Route("/cronjob", name="cron_job", methods={"GET", "POST"})
 */
public function cronJobOnEventDate (EventRepository $eventRepository, EntityManagerInterface $entityManagerInterface) 

{

    $events = $eventRepository->findAll();

    foreach ($events as $event) {

        // set datetime on now and 21HOO
        $actualEventDate = new DateTime('21:00');
        //get actuel event date
        $event->getStartDate();
        // add rendom interval between + 5 to 7 day
        $NewEventDate = $actualEventDate->add(new DateInterval('P' . rand(2,6). 'D'));
        // set the new events date on day + 5 to 7
        $event->setStartDate($NewEventDate);
        
    }

        // enregistrer la nouvelle date
        $event = $entityManagerInterface->flush(); 

        return $this->redirectToRoute('main_home');
}


}