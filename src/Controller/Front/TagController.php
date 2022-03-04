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
     * @Route("/tag/{slug}", name="show_tag", methods={"GET"})
     */
    public function showByName(TagRepository $tagRepository, EventRepository $eventRepository, string $slug): Response
    {
        // We get the tag by his slug
        $tag = $tagRepository->findOneBy(['slug' => $slug]);

        // Display all events by custom query SQL in TagRepository
        $events = $tagRepository->findEventsDQL($tag);
      
        
        return $this->render('front/main/tag.html.twig', compact('tag', 'events'));
    }
}
