<?php

namespace App\Controller\Front;

use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\{ Request, Response};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    /**
     * @Route("/front/api/filters/{locality}", name="api_filters")
     * 
     * @return Response
     */
    public function getEvents(EventRepository $eventRepository, Request $request, string $locality): Response
    {
        // on récupère la clé 'filtre' de la requête
        $filters = $request->get('filters');

        // on s'assure que cette clé est un tableau
        if (is_array($filters))
        {
            // if locality exists we retrieve its events, otherwise we display all events
            $events = ($locality === null || $locality === '') ? $eventRepository->findByLocality($filters) : $eventRepository->findEventsByLocality($filters, $locality);
            return $this->json($events, 200, [], ["groups" => "events"]);
        }
        // si ce n'est pas un tableau alors elle est vide et on retourne la totalité des events (pour avoir du contenu)
        return $this->json($eventRepository->findAll(), 200, [], ["groups" => "events"]);
    }
}
