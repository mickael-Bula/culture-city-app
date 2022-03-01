<?php

namespace App\Controller\Front;


use App\Repository\{ CategoryRepository, EventRepository, UserRepository };
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/")
     * @Route("/home", name="home")
     */
    public function index(CategoryRepository $categoryRepository, EventRepository $eventRepository, UserRepository $userRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $events = $eventRepository->findAll();
        $users = $userRepository->findAll();
        return $this->render('front/main/home.html.twig', compact('events', 'categories','users'));
    }
}