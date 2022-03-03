<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Event;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
// add this use to upload File Type
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [

                'label' => 'Nom de l\'événement',
                'required' => true,   
            ])   

            ->add('price', NumberType::class, [

                'label' => 'Tarif',
                'required' => false,     
            ])  

            ->add('description', TextareaType::class, [

                'label' => 'Description de votre évènement...',
                'required' => false,     
            ])   

            ->add('isPremium', CheckboxType::class, [

                'label' => 'Souhaitez vous mettre votre évènement à la une ?',
                'required' => false, 
            ])

            ->add('startDate', DateTimeType::class, [

                'label' => 'Date et heure de votre évènement',
                'required' => true, 
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                    'hour' => 'Heure', 'minute' => 'Minute',
                ],    
            ])  

            ->add('endDate', DateTimeType::class, [

                'label' => 'Date et heure de fin de votre évènement (Optionnel !)',
                'required' => false,
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                    'hour' => 'Heure', 'minute' => 'Minute',
                ],       
            ])  
           
            // upload user event picture file
            ->add('picture' , FileType::class, [

                'label' => 'Image de votre évènement (Format: jpg, png ou gif file)',
                'required' => true,
                'mapped' => true,
            ])  

            // select event category
            ->add('category', EntityType::class, [

                'class' => Category::class,
                'label' => 'Associez une catégorie à votre évènement',
                'choice_label' => 'name',
                'multiple' => false,
                'required' => true,
            ]) 
            
            // select tags for event
            ->add('tags', EntityType::class, [

                'class' => Tag::class,
                'label' => 'Vous pouvez aussi associer votre événement à des mots clés !',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ]) 
            
            ->add('publier', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
