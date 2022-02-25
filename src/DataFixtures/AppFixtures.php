<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\DBAL\Connection;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker\Factory as Faker;

class AppFixtures extends Fixture
{

    public function __construct(Connection $connexion, UserPasswordHasherInterface $hasheur, SluggerInterface $slugger)
    {
        $this->connexion = $connexion;
        $this->hasheur = $hasheur;
        $this->slugger = $slugger;
    }

    private function truncate()
    {
        $this->connexion->executeQuery('SET foreign_key_checks = 0');

        $this->connexion->executeQuery('TRUNCATE TABLE category');
        $this->connexion->executeQuery('TRUNCATE TABLE event');
        $this->connexion->executeQuery('TRUNCATE TABLE tag');
        $this->connexion->executeQuery('TRUNCATE TABLE user');
        $this->connexion->executeQuery('TRUNCATE TABLE post');
        $this->connexion->executeQuery('TRUNCATE TABLE event_tag');
    }

    public function load(ObjectManager $manager): void
    {
        $this->truncate();
        $faker = Faker::create('fr_FR');

        /************* Category **************/

        $allCategoriesEntity = []; // to add category to events
        $categories = [
            'Concert', 'Spectacle', 'Exposition', 'Loisir', 'Évènementiel'
        ];

        foreach ($categories as $categoryName){

            $category = new Category();
            $category->setName($categoryName)
                ->setSlug(strtolower($this->slugger->slug($category->getName())));

            $allCategoriesEntity[] = $category;

            $manager->persist($category);
        }

        /************* Tag **************/

        $allTagsEntity = []; // to add tags to events 
        $tags = [
            'Jeunesse', 'Rock', 'Jazz', 'Piano', 'Jeux-vidéos', 'Théâtre', 'Comédie', 
        ];

        foreach ($tags as $tagName){
            $tag = new Tag();
            $tag->setName($tagName)
                ->setSlug(strtolower($this->slugger->slug($tag->getName())));

            $allTagsEntity[] = $tag;

            $manager->persist($tag);
        }

        $manager->flush();
    }
}
