<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\AdvertiserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdvertiserController extends AbstractController
{
    /**
     * @Route("/advertiser/panel/edit/profile", name="app_user_advertiser", methods={"GET", "POST"})
     */
    public function editPlaceProfile(EntityManagerInterface $entityManager, Request $request): Response
    {
        // get user from session
        $user = $this->getUser();
        
        // if no user authenticated, we create a new one
        if (!$user)
        {
            $user = new User();
            dump($user);
        }

        // get advertiserForm and bind the authenticated user
        $form = $this->createForm(AdvertiserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // display a success message
            $this->addFlash('success', 'votre profil a été édité');

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('front/advertiser/index.html.twig', compact('form'));
    }
}
