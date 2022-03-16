<?php

namespace App\Controller\Front;

use Symfony\Component\HttpFoundation\{ Request, Response};
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\{ CategoryRepository, EventRepository };
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home" , methods={"GET", "POST"})
     */
    public function showHomePage(CategoryRepository $categoryRepository, EventRepository $eventRepository, Request $request): Response
    {
        $categories = $categoryRepository->findAll();

        // get locality from cookies
        $locality = $request->cookies->get('locality');

        // if locality exists we retrieve its events, otherwise we display all events
        $events = ($locality === null) ? $eventRepository->findAllByStartDate() : $eventRepository->findByLocality($locality);
        dump($events);
 
        // Je récupère la date du jour
        $currentDate = new \DateTime('now');
        $currentDate = $currentDate->format('Y-m-d');

        // On stocke les événements dans 2 tableaux
        $currentEvents = [];
        $upcomingEvents = [];

        // Formattage des dates pour comparaison de chaque event et stockage des events dans chaque tableau
        foreach ($events as $event)
        {  
            $date = $event->getEndDate() ? $event->getEndDate() : $event->getStartDate();   
            $date = $date->format('Y-m-d');

            $dateEvent = $event->getStartDate();
            $dateEvent = $dateEvent->format('Y-m-d');

            if ($date >= $currentDate && $dateEvent <= $currentDate)
            {                
                $currentEvents[] = $event;    
            } 
            elseif ($dateEvent > $currentDate)
            {                
                $upcomingEvents[] = $event;           
            } 
        }

        $premiumEvents = $eventRepository->findBy(['isPremium'=> 'true'], ['createdAt' => 'DESC'], 3);

        return $this->render('front/main/home.html.twig', compact('categories', 'currentEvents', 'upcomingEvents', 'premiumEvents'));
    }

  
    /**
    * @Route("/a-propos", name="main_a_propos")
    */

    public function showAboutPage() {

        return $this->render('front/main/about.html.twig');
    }



}