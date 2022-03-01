<?php

namespace App\Controller\Front;


use App\Entity\Event;
use App\Form\EventType;
use App\Entity\Category;
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
     * @Route("/front/event", name="app_front_event")
     */
    public function index(): Response
    {
        return $this->render('----------', [
            'controller_name' => 'FrontEventController',
        ]);
    }


    /**
     * @Route("/create/event", name="create_event", methods={"GET", "POST"})
     */
    public function createEvent(EntityManagerInterface $entityManager, 
        Request $request, 
        SluggerInterface $slugger): Response
    {
        $form = $this->createForm(EventType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
        
        { 
                $event = New Event();

                $user = $this->getUser();
                $event->setUser($user);

                $name = $form->get('name')->getData();
                $price = $form->get('price')->getData();
                $description = $form->get('description')->getData();
                $isPremium = $form->get('isPremium')->getData();
                $startDate = $form->get('startDate')->getData();
                $endDate = $form->get('endDate')->getData();
                $eventFile = $form->get('picture')->getData();
                $category = $form->get('category')->getData();
                $slug = $slugger->slug($name);

                $event->setName($name)
                    ->setPrice($price)
                    ->setDescription($description)
                    ->setIsPremium($isPremium)
                    ->setStartDate($startDate)
                    ->setStartDate($startDate)
                    ->setEndDate($endDate)
                    ->setCategory($category)
                    ->setSlug(strtolower($slug));   
                    
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
                
            $this->addFlash('event_create', 'votre événement a été crée');

            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

            return $this->renderForm('front/form/event.html.twig', compact('form'));
    }




}
