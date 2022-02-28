<?php

namespace App\Controller\Front;

use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\{ Request, Response};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    /**
     * @Route("/front/api/filters/{category}", name="api_category")
     * 
     * @param string $category
     * @return Response
     */
    public function getEventsByCategory(EventRepository $eventRepository, string $category): Response
    {
        $events = $eventRepository->findByCategory($category);
        return $this->json($events, 200, [], ["groups" => "events"]);
    }

    /**
     * @Route("/front/api/filters", name="api_filters")
     * 
     * @return Response
     */
    public function getEvents(EventRepository $eventRepository, Request $request): Response
    {
        // on récupère la clé 'filtre' de la requête
        $filters = $request->get('filters');

        // on s'assure que cette clé est un tableau
        if (is_array($filters))
        {
            $events = $eventRepository->findEvents($filters);
            return $this->json($events, 200, [], ["groups" => "events"]);
        }
        // si ce n'est pas un tableau alors elle est vide et on retourne la totalité des events (pour avoir du contenu)
        return $this->json($eventRepository->findAll(), 200, [], ["groups" => "events"]);
    }
}
