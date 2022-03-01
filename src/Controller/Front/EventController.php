<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @Route("/front/event", name="app_front_event")
     */
    public function index(): Response
    {
        return $this->render('----------', [
            'controller_name' => 'FrontEventController',
        ]);
    }





}
