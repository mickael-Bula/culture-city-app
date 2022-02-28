<?php

namespace App\Controller\User;

use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        dump($error);
        // last username entered by the user
        $last_username = $authenticationUtils->getLastUsername();
        dump($last_username);
        //TODO A ajouter dans home
        $this->addFlash('success-login', 'Vous êtes bien connecté !');
       
        return $this->render('user/login.html.twig', [
            'last_username' => $last_username, 
            'error' => $error
        ]);

    }



    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
