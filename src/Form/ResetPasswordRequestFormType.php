<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordRequestFormType extends AbstractType
{   
    /**
     * On this form Method.
     * User can make a request to ask for reseting his password.
     * the user must enter his email address to be identified. 
     * It is on this mail that will be sent the but containing the link making it possible to renew its password.
     *  @link resetPasswordController
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['autocomplete' => 'email'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir votre email',
                    ]),
                ],
            ])
        ;
    }

    /**
     * Actually this method validates options and merges them with default values
     *
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
