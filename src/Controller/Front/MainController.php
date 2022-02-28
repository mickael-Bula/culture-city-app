<?php

namespace App\Controller\Front;

use App\Repository\{ CategoryRepository, EventRepository };
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/")
     * @Route("/home", name="home")
     */
    public function index(CategoryRepository $categoryRepository, EventRepository $eventRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $events = $eventRepository->findAll();
        return $this->render('front/main/home.html.twig', compact('events', 'categories'));
    }
}
