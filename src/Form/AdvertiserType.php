<?php

namespace App\Form;

use App\Entity\User;
use DateTimeImmutable;
use Symfony\Component\Form\AbstractType;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
// add this use to upload File Type
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class AdvertiserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                    // upload user avatar file
            ->add('avatar' , FileType::class, [

                'label' => 'Votre image de profil (Format: jpg, png ou gif file)',
                    // unmapped signifie que ce champ n'est associé à aucune propriété d'entité
                'mapped' => false,
                    // ne pas mettre de contrainte de 'requiere' permettra de ne pas demander
                    // d'ajouter systèmatiquement un fichier si on utilise ce même formulaire pour que le user mette simplement
                    // à jour son profil.
                'required' => false,
                    // les champs 'unmapped' ne peuvent pas définir leur contraintes de validation ici
                    // dans l'entité associée, on devra utiliser les classes de contraintes @Assert\File(les contraintes)
                    // pour restraindr les extentions de fichier autorisées, les dimentions min max du fichier etc.
               
            ])      

            // upload user banner file    
            ->add('banner', FileType::class, [

                'label' => 'Votre banière de page profil (Format: jpg, png ou gif file)',
                'mapped' => false,
                'required' => false,
                   
            ])   


            ->add('placeName', TextType::class, [

                'label' => 'Le nom de votre structure...',
                'required' => true,     
            ])   

            ->add('description', TextareaType::class, [

                'label' => 'Décrivez votre structure...',
                'required' => true,     
            ])   

            ->add('address_1', TextType::class, [

                'label' => 'Adresse 1',
                'required' => true,     
            ])   

            ->add('address_2', TextType::class, [

                'label' => 'Adresse 2',
                'required' => true,     
            ])   


            ->add('city', TextType::class, [

                'label' => 'Ville',
                'required' => true,     
            ])   


            ->add('zip', NumberType::class, [

                'label' => 'Code Postal',
                'required' => true,     
            ])  


            ->add('siren', NumberType::class, [

                'label' => 'Siren',
                'required' => true,     
            ]) 


              ->add('phone', TextType::class, [

                'label' => 'Téléphone',
                'required' => true,     
            ]) 

            ->add('foundedIn', DateType::class, [

                'label' => 'Date de fondation',
                'required' => true,     
            ])  

            
            ->add('capacity', NumberType::class, [

                'label' => 'Capacité d\'acceuil',
                'required' => true,     
            ]) 

            ->add('website', UrlType::class, [

                'label' => 'Site web',
                'required' => true,     
            ]) 

            ->add('facebook', UrlType::class, [

                'label' => 'Facebook',
                'required' => true,     
            ]) 

            ->add('twitter', UrlType::class, [

                'label' => 'Twitter',
                'required' => true,     
            ]) 

            ->add('instagram', UrlType::class, [

                'label' => 'Instagram',
                'required' => true,     
            ]) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
