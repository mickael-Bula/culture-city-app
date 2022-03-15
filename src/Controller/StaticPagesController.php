<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticPagesController extends AbstractController
{
    /**
     * @Route("/privacy-statement", name="app_static_pages")
     */
    public function legalNotice(): Response
    {
        return $this->render('static_pages/legal_notice.html.twig');
    }
}
