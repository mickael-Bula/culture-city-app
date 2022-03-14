<?php 

namespace App\EventSubscriber;

use App\Entity\Tag;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Event;
use DateTimeImmutable;
use App\Entity\Category;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $slugger;
    private $passwordEncoder;

    public function __construct(SluggerInterface $slugger, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->slugger = $slugger;
        $this->passwordEncoder = $passwordEncoder;

    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setData'],
            BeforeEntityUpdatedEvent::class => ['updateData']
        ];
    }

    /**
     * Some actions when setting new data in CRUD Admin 
     *
     * @param BeforeEntityPersistedEvent $event
     * @return void
     */
    public function setData(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (($entity instanceof Category || $entity instanceof Tag || $entity instanceof User || $entity instanceof Event)) 
        {
            $slug = $this->slugger->slug($entity->getName());
            $entity->setSlug(strtolower($slug));
        }

        if (($entity instanceof User))
        {
            if ($entity->getStatus() == 1)
            {
                $entity->setRoles(['ROLE_ANNONCEUR']);
            } else {
                $entity->setRoles(['ROLE_USER']);
            }

            $this->setPassword($entity);
        }

        if (($entity instanceof User || $entity instanceof Post || $entity instanceof Event))
        {
            $now = new DateTimeImmutable('now');
            $entity->setCreatedAt($now);
        }
    }

    /**
     * Some actions when updating data in CRUD Admin
     *
     * @param BeforeEntityUpdatedEvent $event
     * @return void
     */
    public function updateData(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (($entity instanceof Category || $entity instanceof Tag || $entity instanceof User || $entity instanceof Event)) 
        {
            $slug = $this->slugger->slug($entity->getName());
            $entity->setSlug(strtolower($slug));
        }

        if (($entity instanceof User))
        {
            if ($entity->getStatus() == 1)
            {
                $entity->setRoles(['ROLE_ANNONCEUR']);
            } else {
                $entity->setRoles(['ROLE_USER']);
            }

            $this->setPassword($entity);
        }

        if (($entity instanceof User || $entity instanceof Post || $entity instanceof Event))
        {
            $now = new DateTimeImmutable('now');
            $entity->setUpdatedAt($now);
        }
    }

    /**
     * Method for hashing password
     * 
     * @param User $entity
     */
    public function setPassword(User $entity): void
    {
        $pass = $entity->getPassword();

        $entity->setPassword(
            $this->passwordEncoder->hashPassword($entity, $pass));
    }
}