<?php

namespace App\Controller\User;

use App\Entity\User;
use DateTimeImmutable;
use App\Form\registrationType;
use App\Security\EmailVerifier;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;


class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("register", name="user_register")
     */
    public function register(Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        EntityManagerInterface $entityManager, 
        LoginFormAuthenticator $authenticator, 
        UserAuthenticatorInterface $userAuthenticator, 
        SluggerInterface $slugger,
        MailerInterface $mailer): Response
    {
        $user = new User($slugger);
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //dd($form);
            $form->get('password')->getData();
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $defaultUserRole = ['ROLE_USER'];
            $user->setRoles($defaultUserRole);

            $newUserName = $form->get('name')->getData();
            $user->setName($newUserName);

            $created = new DateTimeImmutable('now');
            $user->setCreatedAt($created);
            
            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email send to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('info@culturecity.fr', '"L\'équipe de Culure City"'))
                    ->to($user->getEmail())
                    ->subject('Merci de confirmer votre adresse e-mail')
                    ->htmlTemplate('user/confirmation_email.html.twig')
            );

            // todo nous faire envoyer un mail d'info ici pour l'admin qui doit savoir qu'un user s'est enregistré
            // dans un second temps on pourra faire un service pour ça...

            //todo si la checkbox est coché il faut rediriger vers la suite du formulaire pour éditer son profil.
            if ( $form->get('status')->getData() == true) {

                $email = (new Email())
                    ->from('register@yculturecity.fr')
                    ->to('admin@yculturecity.fr')
                    //->cc('cc@example.com')
                    //->bcc('bcc@example.com')
                    //->replyTo('fabien@example.com')
                    //->priority(Email::PRIORITY_HIGH)
                    ->subject('Demande de statut annonceur')
                    ->text('Un nouvel utilsateur a fait une demande pour annoncer des évéènements sur App Culture City!');
                    //->html('<p>See Twig integration for better HTML integration!</p>');
        
                $mailer->send($email);

                $userAuthenticator->authenticateUser(
                    $user,
                    $authenticator,
                    $request
                );

                return $this->redirectToRoute('app_user_advertizer');
    
                }

                $userAuthenticator->authenticateUser(
                    $user,
                    $authenticator,
                    $request
                );

                return $this->redirectToRoute('home');
                
                }

                return $this->render('user/registration.html.twig', [
                    'registration' => $form->createView(),
                ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
     public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('user_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('user_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('user_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('emailverification', 'Votre adresse email a été confirmée !');

        return $this->redirectToRoute('home');
    } 



}
