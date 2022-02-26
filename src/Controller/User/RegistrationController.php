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

            $form->get('password')->getData();
            // password hash on user password value set by user on register form.
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            // set a defaultUserRole for user.role and persit this role on flush.
            $defaultUserRole = ['ROLE_USER'];
            $user->setRoles($defaultUserRole);

            // set user.name and persit this on flush.
            $newUserName = $form->get('name')->getData();
            $user->setName($newUserName);

            // set user.createdAt on NOW and persit this on flush.
            $created = new DateTimeImmutable('now');
            $user->setCreatedAt($created);
            
            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email send to the user
            $this->emailVerifier->sendEmailConfirmation ('app_verify_email', $user,
            // here we use the template confirmation_email.html.twig   
            (new TemplatedEmail())

                    ->from(new Address('info@culturecity.fr', '"L\'équipe de Culure City"'))
                    ->to($user->getEmail())
                    ->subject('Merci de confirmer votre adresse e-mail')
                    ->htmlTemplate('user/confirmation_email.html.twig')
            );


                  if ( $form->get('status')->getData() == true) {
                    // here we don't use any email template -> juste new Email()    
                            $email = (new Email())
                            ->from('register@yculturecity.fr')
                            ->to('admin@yculturecity.fr')
                                //->cc('cc@example.com')
                                //->bcc('bcc@example.com')
                                //->replyTo('fabien@example.com')
                                //->priority(Email::PRIORITY_HIGH)
                            ->subject('Demande de statut annonceur')
                            ->text('Un nouvel utilisateur ' . $newUserName .' vient de s\'enregistrer sur App Culture City et demande le statut annonceur!');
                        //->html('<p>See Twig integration for better HTML integration!</p>');
                            $mailer->send($email);
                        
                        // user advertiser autologin after registration send
                        $userAuthenticator->authenticateUser(
                            $user,
                            $authenticator,
                            $request
                        );

                        // flash message for user with status = advertiser request
                        $this->addFlash('success-register-annonceur', 'Merci ' . $newUserName . ' vous êtes enregistré et connecté.
                        Nous avons bien reçu votre demande afin d\'annoncer vos événements. Nous allons valider votre statut annonceur pour
                        que vous puissiez commencer à partager vos événements !.
                        En attendant...veuillez vérifier vos mail pour confirmer votre adresse e-mail !');

                        return $this->redirectToRoute('app_user_advertiser');
            
                        }


                // user autologin after registration send
                $userAuthenticator->authenticateUser(
                    $user,
                    $authenticator,
                    $request
                );


                // flash message for user on home avec register and auto-login success
                $this->addFlash('success-register-user', 'Merci ' . $newUserName . ' vous êtes enregistré et connecté. Veuillez vérifier vos mail pour confirmer votre adresse e-mail !');

                //redirect on home page after register and autologin.
                return $this->redirectToRoute('home');
                
                }

                
                // First action when anonyme user visit road /register
                // we display the registration.html.twig
                return $this->render('user/registration.html.twig', [
                    'registration' => $form->createView(),
                ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
     public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        //here id add an id in the mail so user can confirm his mail on every support
        //he is not obliged to open the email on the device from which he has just created his account. 
        //For example, he can register from a computer and confirm his email address from his smartphone.
        $id = $request->get('id');

        // if id not present on the mail we redirect user on /register
        if (null === $id) {
            return $this->redirectToRoute('user_register');
        }

        // we find the curent user by Id. 
        $user = $userRepository->find($id);

        // if curent user does not exist, we redirect the user on /register
        if (null === $user) {
            return $this->redirectToRoute('user_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        // after user click on link in the validation email he received
        // we set is_verified property to 1 and persist it in database for curent user.

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());
        // if there is erros we have a flash message here we can use
        // we redirect the user on /register.    
            return $this->redirectToRoute('user_register');
        }

        // after user click on link in the validation email he received.
        // flash message to confirm the user that his password is verified. 
        // Message actually displayed on home.
        $this->addFlash('emailverification', 'Votre adresse email a été confirmée !');

        return $this->redirectToRoute('home');
    } 



}
