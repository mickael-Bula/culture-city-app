<?php

namespace App\Controller\Front;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="app_front_contact")
     */
    public function show(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $contactFormData = $form->getData();

            //dd($contactFormData);

            $message = (new Email())
                        ->from($contactFormData['email'])
                        ->to('culturecityapp@gmail.com')
                        ->subject('mail du site culture city app')
                        ->text ('nom : ' . $contactFormData['name'] . \PHP_EOL .
                            'mail : '. $contactFormData['email'] . \PHP_EOL . 
                            $contactFormData['message'], 'text/plain' );
            
            $mailer->send($message);

            $this->addFlash('success', 'Votre message a bien été envoyé !');

            return $this->redirectToRoute('app_front_contact');

        }
        
        return $this->render('front/contact/index.html.twig', [
            'contactForm' =>$form->createView(),
        ]);
    }
}
