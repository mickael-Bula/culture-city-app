<?php

namespace App\Controller\Front;

use App\Entity\Post;
use App\Form\PostType;
use DateTimeImmutable;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/event/{slug}/post", name="event_post_comment", methods={"GET", "POST"})
     */
    public function postNewCommentOnCurentEvent(EntityManagerInterface $entityManager, 
    Request $request, 
    EventRepository $eventRepository,
    string $slug
    ): Response
    {
        // get user from session
        $user = $this->getUser();

        // if no user authenticated
        if (!$user) {
            $this->addFlash('unautorized-comment', "Vous devez être connecté pour commenter cet événement !");
            $this->redirectToRoute('user_register', [], Response::HTTP_MOVED_PERMANENTLY);
        }

        // get the curent event
        $event = $eventRepository->findOneBy(['slug' => $slug]);

        //set empty new post Object
        $post = new Post();

        // get postType and bind new Post object on curent event
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
                   
        if ($form->isSubmitted() && $form->isValid()) {

            $postCreatedAt = new DateTimeImmutable('now');
            $post->setCreatedAt($postCreatedAt);
            
            //set post author xith curent user from session and link current post to current event.
            $post->setAuthor($user)
                 ->setEvent($event);

            //persist the new comment in dataBase.            
            $entityManager->persist($post);
            $entityManager->flush();

            // get event name for dynamic event name in flash message.
            $eventname = $event->getName();
            $this->addFlash('succes-comment', 'Votre commentaire a été ajouté à ' . $eventname . ' !');

            //return user on commented event
            return $this->redirectToRoute('show_event', ['slug'=> $event->getSlug()], Response::HTTP_SEE_OTHER);
        }
       
        return $this->renderForm('front/form/post_comment.html.twig', compact('form' , 'event'));
         
    }
}
