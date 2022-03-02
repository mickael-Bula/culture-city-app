<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
// add this use to upload File Type
use Symfony\Component\Form\Extension\Core\Type\FileType;

class AdvertiserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // upload user avatar file
            ->add('avatar' , FileType::class, [

                'label' => 'Votre image de profil (Format: jpg, png ou gif file)',
                    // unmapped signifie que ce champ n'est associé à aucune propriété d'entité
                'mapped' => true,
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
                    // unmapped signifie que ce champ n'est associé à aucune propriété d'entité
                'mapped' => true,
                    // ne pas mettre de contrainte de 'requiere' permettra de ne pas demander
                    // d'ajouter systèmatiquement un fichier si on utilise ce même formulaire pour que le user mette simplement
                    // à jour son profil.
                'required' => false,
                    // les champs 'unmapped' ne peuvent pas définir leur contraintes de validation ici
                    // dans l'entité associée, on devra utiliser les classes de contraintes @Assert\File(les contraintes)
                    // pour restraindr les extentions de fichier autorisées, les dimentions min max du fichier etc.
            ])   



            ->add('address_1')
            ->add('address_2')
            ->add('city')
            ->add('zip')
            ->add('siren')
            ->add('phone')
            ->add('foundedIn')
            ->add('website')
            ->add('capacity')
            ->add('facebook')
            ->add('twitter')
            ->add('instagram')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
