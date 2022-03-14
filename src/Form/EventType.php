<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Event;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{ DateTimeType, FileType, TextType, NumberType, SubmitType, CheckboxType, TextareaType };

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('name', TextType::class, [

                'label' => 'Nom',
                'required' => true,   
                'empty_data' => 'Non renseigné', 
  
            ])   

            ->add('price', NumberType::class, [

                'label' => 'Tarif',
                'required' => true,     
            ])  

            ->add('description', CKEditorType::class, [

                'label' => 'Décrivez votre événement...',
                'required' => true,     
            ])   

            ->add('startDate', DateTimeType::class, [

                'widget' => 'single_text',
                'label' => 'Date de votre événement',
                'required' => true,     
                //'data' => new \DateTime(),
            ])  

            ->add('endDate', DateTimeType::class, [

                'widget' => 'single_text',
                'label' => 'Date de fin de votre événement',
                'required' => false,     
                //'data' => new \DateTime(),
            ])  
           
            // upload event picture file
            ->add('picture' , FileType::class, [

                'label' => 'Ajoutez l\'image de votre événement',
                'mapped' => false,
                'required' => false,  

                //contraint valid image file type 
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
