<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Event;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\DBAL\Connection;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker\Factory as Faker;

class AppFixtures extends Fixture
{

    private $connexion;
    private $hasher;
    private $slugger;

    public function __construct(Connection $connexion, UserPasswordHasherInterface $hasher, SluggerInterface $slugger)
    {
        $this->connexion = $connexion;
        $this->hasheur = $hasher;
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

        /************* User **************/

        /* ANNONCEUR */

        $allAnnonceursEntity = []; // to add Annonceurs to Events

        for ($i=1; $i <= 20; $i++){

            $newAnnonceur = new User($this->slugger);
            $newAnnonceur->setEmail('annonceur' . $i . '@annonceur.fr')
                ->setRoles(['ROLE_ANNONCEUR'])
                ->setName($faker->firstName(rand(1, 2) == 1 ? 'female' : 'male'))
                ->setPassword(strtolower($newAnnonceur->getName()))
                ->setCreatedAt(new DateTimeImmutable('now'))
                ->setIsVerified(rand(1, 2))
                ->setAddress1($faker->streetAddress())
                ->setAddress2($faker->streetAddress())
                ->setCity($faker->city)
                ->setZip($faker->postcode())
                ->setSiren($faker->randomNumber())
                ->setAvatar('avatar.jpeg')
                ->setBanner('banner.jpeg')
                ->setPhone($faker->phoneNumber())
                ->setFoundedIn(new DateTimeImmutable($faker->date()))
                ->setWebsite('www.lieu.fr')
                ->setCapacity($faker->randomNumber())
                ->setFacebook('www.facebook.com/lieu')
                ->setInstagram('www.instagram.com/lieu')
                ->setTwitter('www.twitter.com/lieu')
                ->setSlug(strtolower($this->slugger->slug($newAnnonceur->getName())));

                $allAnnonceursEntity[] = $newAnnonceur;

                $manager->persist($newAnnonceur);
        }

        /* USER */

        $allUsersEntity = []; // to add Users to Post

        for ($i=1; $i <= 10; $i++){
            $newUser = new User($this->slugger);
            $newUser->setEmail('user'. $i .'@user.com')
                ->setRoles(['ROLE_USER'])
                ->setPassword('user')
                ->setCreatedAt(new DateTimeImmutable('now'))
                ->setName($faker->firstName(rand(1, 2) == 1 ? 'female' : 'male'))
                ->setSlug(strtolower($this->slugger->slug($newUser->getName())));

                $allUsersEntity[] = $newUser;

            $manager->persist($newUser);
        }

        /* ADMIN */
        $newAdmin = new User($this->slugger);
        $newAdmin->setEmail('admin@admin.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword('admin')
            ->setCreatedAt(new DateTimeImmutable('now'));

            $manager->persist($newAdmin);
    

        /************* Event **************/

        $allEventsEntity = []; // to add Event to Post

        for ($i=1; $i <= 10; $i++){

            $newEvent = new Event();

            $randomCategory = $allCategoriesEntity[mt_rand(0, count($allCategoriesEntity) - 1)];
            $randomAnnonceur = $allAnnonceursEntity[mt_rand(0, count($allAnnonceursEntity) - 1)];
            $randomTag = $allTagsEntity[mt_rand(0, count($allTagsEntity) - 1)];
        
            $newEvent->setName($faker->words(3, true))
                ->setCreatedAt(new DateTimeImmutable('now'))
                ->setCategory($randomCategory)
                ->setUser($randomAnnonceur)
                ->setPrice($faker->numberBetween(0, 20))
                ->setDescription($faker->text())
                ->setPicture('https://media.istockphoto.com/photos/hot-air-balloons-of-cappadocia-picture-id536290479?k=20&m=536290479&s=612x612&w=0&h=3QITRcn07NUf1WehQfvdKg0N0iqUGBUzI7RurIKT4oY=')
                ->setIsPremium($faker->numberBetween(0, 1))
                ->setStartDate(new DateTime())
                ->setEndDate(new DateTime())
                ->setSlug(strtolower($this->slugger->slug($newEvent->getName())))
                ->addTag($randomTag);

                $allEventsEntity[] = $newEvent;
                
            $manager->persist($newEvent);
        }

        /************* Post **************/

        for ($i=1; $i <= 50; $i++){

            $randomUser = $allUsersEntity[mt_rand(0, count($allUsersEntity) - 1)];
            $randomEvent = $allEventsEntity[mt_rand(0, count($allEventsEntity) - 1)];

            $newPost = new Post();

            $newPost->setContent($faker->paragraph(5))
                ->setCreatedAt(new DateTimeImmutable('now'))
                ->setAuthor($randomUser)
                ->setEvent($randomEvent);
                
            $manager->persist($newPost);
        }

        $manager->flush();
    }
}
