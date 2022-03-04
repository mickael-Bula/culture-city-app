<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('facebook')
            ->add('twitter')
            ->add('instagram')
            // upload user avatar file
            ->add('avatar' , FileType::class, [

                'label' => 'Ajoutez votre image de profil',
                'mapped' => false,
                'required' => false,

                'constraints' => [
                    new Image([
                        'maxSize' => '1024k',
                        'minWidth' => '200',
                        'maxWidth' => '1200',
                        'minHeight' => '200',
                        'maxHeight' => '1200',
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
                        'minWidth' => '800',
                        'maxWidth' => '3960',
                        'minHeight' => '800',
                        'maxHeight' => '1980',
                    ])
                ]
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
