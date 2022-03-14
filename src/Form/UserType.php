<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image;


class UserType extends AbstractType
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

                'label' => 'Ajoutez une banière à votre page de profil',
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
