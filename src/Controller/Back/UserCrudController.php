<?php

namespace App\Controller\Back;

use App\Entity\User;
use phpDocumentor\Reflection\Types\Boolean;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('slug')->hideOnForm(),
            BooleanField::new('isVerified'),
            BooleanField::new('status'),
            ArrayField::new('roles'),
            TextField::new('address_1')->hideOnIndex(),
            TextField::new('address_2')->hideOnIndex(),
            TextField::new('city')->hideOnIndex(),
            TextField::new('ZIP')->hideOnIndex(),
            TextField::new('siren')->hideOnIndex(),
            TextField::new('phone')->hideOnIndex(),
            DateField::new('founded_in')->hideOnIndex(),
            IntegerField::new('capacity')->hideOnIndex(),
            UrlField::new('website')->hideOnIndex(),
            UrlField::new('facebook')->hideOnIndex(),
            UrlField::new('twitter')->hideOnIndex(),
            UrlField::new('instagram')->hideOnIndex(),
            DateField::new('created_at')->hideOnForm(),
        ];
    }
    
}
