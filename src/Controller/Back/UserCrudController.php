<?php

namespace App\Controller\Back;

use App\Entity\User;

use phpDocumentor\Reflection\Types\Boolean;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
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
            
            TextField::new('name')->setLabel('Prénom'),
            SlugField::new('slug')->setTargetFieldName('name'),
            TextField::new('email')->setLabel('Email'),
            TextField::new('password')->hideOnIndex()->setLabel('Password'),
            ChoiceField::new('isVerified')->setChoices(['Oui' => 1, 'Non' => 0])->setLabel('Mail Vérifié'),
            ChoiceField::new('status')->setChoices(['Oui' => 1, 'Non' => 0])->setLabel('Annonceur'),
            //ArrayField::new('roles')->hideOnIndex()->setLabel('Role'),
            TextField::new('address_1')->hideOnIndex()->setLabel('Adresse 1'),
            TextField::new('address_2')->hideOnIndex()->setLabel('Adresse 2'),
            TextField::new('city')->hideOnIndex()->setLabel('Ville'),
            TextField::new('ZIP')->hideOnIndex()->setLabel('Code Postal'),
            TextField::new('siren')->hideOnIndex(),
            TextField::new('phone')->hideOnIndex()->setLabel('Téléphone'),
            DateField::new('founded_in')->hideOnIndex()->setLabel('Date de création de la structure'),
            IntegerField::new('capacity')->hideOnIndex()->setLabel('Capacité'),
            UrlField::new('website')->hideOnIndex()->setLabel('Site'),
            UrlField::new('facebook')->hideOnIndex(),
            UrlField::new('twitter')->hideOnIndex(),
            UrlField::new('instagram')->hideOnIndex(),
            DateField::new('created_at')->hideOnForm(),
            ImageField::new('avatar','Image')
            ->setBasePath('/media/cache/avatar_50/upload/useravatar')
            ->setUploadDir('public/upload/useravatar')
            ->setUploadedFileNamePattern('[randomhash].[extension]'),
        ];
    }
    
}
