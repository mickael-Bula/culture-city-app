<?php

namespace App\Controller\Front;

use App\Entity\Tag;
use App\Repository\EventRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    /**
     * @Route("/tag/{slug}", name="events_tag", methods={"GET"})
     */
    public function showByName(Tag $tag): Response
    {
       
        return $this->render('front/main/tag.html.twig', compact('tag'));
    }
}
