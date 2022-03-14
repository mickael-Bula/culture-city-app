<?php

namespace App\Controller\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * Method allowing a user to connect on the app.
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $last_username = $authenticationUtils->getLastUsername();
       
        // flash massage on user login actually displayed on home page
        $this->addFlash('success-login', 'Félicitation, vous êtes bien connecté !');
       
        return $this->render('user/login.html.twig', [
            'last_username' => $last_username, 
            'error' => $error,
            // on récupère l'url dans la query string et on passe cette variable au champ caché du formulaire de login
            //comme target path de redirection après login.
            dd($request->headers->get('referer')),
            'redirect_user_after_login' => $request->headers->get('referer'),
            
        ]);

    }



    /**
     * Method allowing a user to disconnect from the app.
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
