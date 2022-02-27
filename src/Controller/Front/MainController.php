<?php

namespace App\Controller\Front;

use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/")
     * @Route("/home", name="home")
     */
    public function index(EventRepository $eventRepository, UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        $events = $eventRepository->findAll();

        dump($users);
        dump($events);

        return $this->render('front/main/home.html.twig', compact('events', 'users'));
    }
}
