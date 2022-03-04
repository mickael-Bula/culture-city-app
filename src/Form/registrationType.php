<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class registrationType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => ['placeholder' =>'Saisissez une adresse email valide'],     
            ])  
            

            ->add('name', TextType::class, [
                'label' => 'Pseudo',
                'required' => true,
                'attr' => ['placeholder' =>'Choisissez votre nom d\'utilisateur'],     
            ])  
            
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'label' => 'Mot de passe',
                'required' => true,
                'first_options' => ['label' => 'mot de passe'],
                'second_options' => ['label' => 'Confirmez votre mot de passe'],
                'attr' => ['placeholder' =>'Choisir votre mot de passe'], 
            ])
            
            ->add('status', CheckboxType::class, [
                'label'    => 'Je veux annoncer des événements',
                'required' => false,
            ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}