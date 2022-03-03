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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [

                'label' => 'Nom',
                'required' => true,     
            ])   

            ->add('price', NumberType::class, [

                'label' => 'Tarif',
                'required' => true,     
            ])  

            ->add('description', TextareaType::class, [

                'label' => 'Décrivez votre évènement...',
                'required' => true,     
            ])   

            ->add('isPremium', CheckboxType::class, [

                'label' => 'Souhaitez vous mettre votre évènement à la une ?',
                'required' => false, 
            ])

            ->add('startDate', DateType::class, [

                'label' => 'Date de votre évènement',
                'required' => true,     
            ])  

            ->add('endDate', DateType::class, [

                'label' => 'Date de fin de votre évènement',
                'required' => true,     
            ])  
           
            // upload user event picture file
            ->add('picture' , FileType::class, [

                'label' => 'Image de votre évènement (Format: jpg, png ou gif file)',
                'mapped' => false,
                'required' => false,     
            ])  

            // select event category
            ->add('category', EntityType::class, [

                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => false,
                'required' => true,
            ]) 
            
            // select tags for event
            ->add('tags', EntityType::class, [

                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                //'required' => false,
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
