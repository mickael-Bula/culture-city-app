<?php

namespace App\Controller\Back;

use App\Entity\Event;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EventCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Event::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('user')->hideOnIndex()->setLabel('Utilisateur'),
            TextField::new('name')->setLabel("Nom de l'évenement"),
            TextField::new('slug')->hideOnForm(),
            AssociationField::new('category')->setLabel('Categorie'),
            AssociationField::new('tags')->setLabel('Tag.s'),
            ChoiceField::new('is_premium')->setChoices(['Oui' => 1, 'Non' => 0])->setLabel('Premium'),
            DateField::new('created_at')->hideOnForm()->setLabel('Crée le'),
            IntegerField::new('price')->hideOnIndex()->setLabel('Prix'),
            TextField::new('description')->hideOnIndex(),
            DateTimeField::new('start_date')->hideOnIndex()->setLabel('Date de début'),
            DateTimeField::new('end_date')->hideOnIndex()->setLabel('Date de fin'),
            ImageField::new('picture','Image')
            ->setBasePath('media/cache/event_picture_300/upload/eventpicture')
            ->setUploadDir('public/upload/eventpicture')
            ->setUploadedFileNamePattern('[randomhash].[extension]'),
        ];
    }
    
}
