<?php

namespace App\Controller\Front;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/front/api", name="api_all")
     */
    public function events(EventRepository $eventRepository): Response
    {
        return $this->json($eventRepository->findAll(), 200, [], ["groups" => "events"]);
    }

    /**
     * @Route("/front/api/{category}", name="api_category")
     * 
     * @param string $category
     * @return Response
     */
    public function getEventsByCategory(EventRepository $eventRepository, string $category): Response
    {
        $events = $eventRepository->findByCategory($category);
        return $this->json($events, 200, [], ["groups" => "events"]);
    }
}
