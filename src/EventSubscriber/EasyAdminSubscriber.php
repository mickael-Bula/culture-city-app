<?php 

namespace App\EventSubscriber;

use App\Entity\Tag;
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
            BeforeEntityPersistedEvent::class => ['setCategorySlug'],
            BeforeEntityPersistedEvent::class => ['setTagSlug'],
            BeforeEntityPersistedEvent::class => ['setUserSlug'],
            BeforeEntityPersistedEvent::class => ['setEventSlugAndDate'],
            BeforeEntityUpdatedEvent::class => ['updateUserRole'],
            BeforeEntityPersistedEvent::class => ['addUser']

        ];
    }

    public function setCategorySlug(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Category)) {
            return;
        }

        $slug = $this->slugger->slug($entity->getName());
        $entity->setSlug(strtolower($slug));
    }

    public function setTagSlug(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Tag)) {
            return;
        }

        $slug = $this->slugger->slug($entity->getName());
        $entity->setSlug(strtolower($slug));
    }

    public function setUserSlug(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }

        $slug = $this->slugger->slug($entity->getName());
        $entity->setSlug(strtolower($slug));
    }

    /**
     * Update UserRole on changing is annoncuer to 1 in EasyAdmin User Edit Panel
     *
     * @param BeforeEntityUpdatedEvent $updateEvent
     * @return void
     */
    public function updateUserRole(BeforeEntityUpdatedEvent $updateEvent)
    {
        $entity = $updateEvent->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }

        if ($entity->getStatus() == 1)
        {
            $entity->setRoles(['ROLE_ANNONCEUR']);
        } else {
            $entity->setRoles(['ROLE_USER']);
        }
    }

    public function setEventSlugAndDate(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Event)) {
            return;
        }

        $slug = $this->slugger->slug($entity->getName());
        $entity->setSlug(strtolower($slug));

        $now = new DateTimeImmutable('now');
        $entity->setCreatedAt($now);
    }

    /**
     * This Method Hash password if create a user in easy admin
     * @link https://grafikart.fr/forum/33951
     *
     * @param BeforeEntityPersistedEvent $event
     * @return void
     */
    public function addUser(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof User)) {
            return;
        }
        $this->setPassword($entity);
    }

    /**
     * @param User $entity
     */
    public function setPassword(User $entity): void
    {
        $pass = $entity->getPassword();

        $entity->setPassword(
            $this->passwordEncoder->hashPassword(
                $entity,
                $pass
            )
        );
    }












}