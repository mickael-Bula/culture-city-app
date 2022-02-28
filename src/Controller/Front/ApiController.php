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
}
