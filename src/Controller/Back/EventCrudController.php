<?php

namespace App\Controller\Back;

use App\Entity\Event;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
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
            AssociationField::new('category')->hideOnIndex(),
            AssociationField::new('user')->hideOnIndex(),
            TextField::new('name'),
            TextField::new('slug')->hideOnForm(),
            IntegerField::new('is_premium')->hideOnIndex(),
            DateField::new('created_at')->hideOnForm(),
            IntegerField::new('price')->hideOnIndex(),
            TextField::new('description')->hideOnIndex(),
            DateTimeField::new('start_date')->hideOnIndex(),
            DateTimeField::new('end_date')->hideOnIndex(),
        ];
    }
    
}
