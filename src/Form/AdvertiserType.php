<?php

namespace App\Form;

use App\Entity\User;
use DateTimeImmutable;
use Symfony\Component\Form\AbstractType;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Image;


class AdvertiserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            // upload user avatar file
            ->add('avatar' , FileType::class, [

                'label' => 'Ajoutez votre image de profil',
                'mapped' => false,
                'required' => false,

                'constraints' => [
                    new Image([
                        'maxSize' => '2024k',
                        'minWidth' => '400',
                        'maxWidth' => '4000',
                        'minHeight' => '400',
                        'maxHeight' => '4000',
                    ])
                ]
             
               
            ])      

            // upload user banner file    
            ->add('banner', FileType::class, [

                'label' => 'Ajoutez une bannière à votre page de profil',
                'mapped' => false,
                'required' => false,

                'constraints' => [
                    new Image([
                        'maxSize' => '2024k',
                        'minWidth' => '400',
                        'maxWidth' => '4000',
                        'minHeight' => '400',
                        'maxHeight' => '4000',
                    ])
                ]
                   
            ])   


            ->add('placeName', TextType::class, [

                'label' => 'Le nom de votre structure...',
                'required' => true,
                //'empty_data' => 'Champ à renseigner !',   
            ])   

            ->add('description', TextareaType::class, [

                'label' => 'Décrivez votre structure...',
                'required' => true,   
                //'empty_data' => 'Champ à renseigner !',        
            ])   

            ->add('address_1',  TextType::class, [

                'label' => 'Adresse 1',
                'required' => true,  
                //'empty_data' => 'Champ à renseigner !',         
            ])   

            ->add('address_2', TextType::class, [

                'label' => 'Adresse 2',
                'required' => true,  
            ])   


            ->add('city', TextType::class, [

                'label' => 'Ville',
                'required' => true, 
                //'empty_data' => 'Champ à renseigner !',           
            ])   


            ->add('zip', TextType::class, [

                'label' => 'Code Postal',
                'required' => true,  
                //'empty_data' => 000000,           
            ])  

            ->add('lat',  TextType::class, [

                'label' => 'Latitude',
                'required' => true       
            ]) 

            ->add('lng',  TextType::class, [

                'label' => 'Longitude',
                'required' => true       
            ]) 


            ->add('siren', NumberType::class, [

                'label' => 'Siren',
                'required' => true, 
                //'empty_data' => 000000,            
            ]) 


              ->add('phone', TextType::class, [

                'label' => 'Téléphone',
                'required' => true, 
                //'empty_data' => 'Champ à renseigner !',           
            ]) 

            ->add('foundedIn', DateType::class, [

                'data' => new \DateTime(),
                'label' => 'Date de fondation',
                'required' => true, 
                //'empty_data' => 'Champ à renseigner !',           
            ])  

            
            ->add('capacity', NumberType::class, [

                'label' => 'Capacité d\'accueil',
                'required' => true,  
                //'empty_data' => 000000,           
            ]) 

            ->add('website', UrlType::class, [

                'label' => 'Site web',
                'required' => true,  
                //'empty_data' => 'Champ à renseigner !',           
            ]) 

            ->add('facebook', UrlType::class, [

                'label' => 'Facebook',
                'required' => true, 
                //'empty_data' => 'Champ à renseigner !',            
            ]) 

            ->add('twitter', UrlType::class, [

                'label' => 'Twitter',
                'required' => true,             
            ]) 

            ->add('instagram', UrlType::class, [

                'label' => 'Instagram',      
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
