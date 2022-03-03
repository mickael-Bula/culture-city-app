<?php

namespace App\Controller\Front;


use App\Entity\Event;
use App\Entity\Tag;
use App\Form\EventType;
use App\Entity\Category;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;



class EventController extends AbstractController
{
    /**
     * @Route("/event/{slug}", name="show_event")
     */
    public function showEventBySlug(EventRepository $eventRepository, string $slug): Response
    {
        $event = $eventRepository->findOneBy(["slug" => $slug]);
        if (!$event) {
            throw $this->createNotFoundException('Il n\'y a pas d\'événement');
        }
        return $this->render('front/main/event.html.twig', compact('event'));
    }

    /**
     * @Route("/create/event", name="create_event", methods={"GET", "POST"})
     */
    public function createEvent(EntityManagerInterface $entityManager, 
            Request $request, 
            SluggerInterface $slugger
            ): Response 
        {
        
        // New Empty event
        $event = New Event();
        
        // Automatically initialise this new Event with form values
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())

        {           
                $user = $this->getUser();
                $event->setUser($user);

                // need to sluggify event by event name property
                $name = $form->get('name')->getData();
                $slug = $slugger->slug($name);
                $event->setSlug(strtolower($slug));

                //! Au cas ou on utilise ce form pour mettre à jour l'événement 
                //! on ne passe ici que si il y a eu une image modifiée.

                $eventFile = $form->get('picture')->getData();

                if ($form->get('picture')->getData() != null) {

                    if ($eventFile) {
                        $originalFilename = pathinfo($eventFile->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeFilename = $slugger->slug($originalFilename);
                        $newFilename = $safeFilename.'-'.uniqid().'.'.$eventFile->guessExtension();
        
                        try {
                            $eventFile->move(
                                $this->getParameter('event_picture'),
                                $newFilename
                            );
                        } catch (FileException $e) {
                            // ... gérer les exeptions si problème d'upload en fonction des restrictions qu'on a pu donner dans le form
                        }
        
                        $event->setPicture($newFilename);
                    }
                }

            $this->addFlash('event_create', 'votre événement a été crée');

            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('main_home', [], Response::HTTP_SEE_OTHER);
        }

            return $this->renderForm('front/form/event.html.twig', compact('form'));
    }

}
