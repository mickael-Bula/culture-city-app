<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertiserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('isVerified')
            ->add('address_1')
            ->add('address_2')
            ->add('city')
            ->add('zip')
            ->add('siren')
            ->add('avatar')
            ->add('banner')
            ->add('phone')
            ->add('foundedIn')
            ->add('website')
            ->add('capacity')
            ->add('facebook')
            ->add('twitter')
            ->add('instagram')
            ->add('slug')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
