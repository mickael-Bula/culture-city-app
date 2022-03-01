<?php 

namespace App\EventSubscriber;

use App\Entity\Category;
use App\Entity\Tag;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setCategorySlug'],
            BeforeEntityPersistedEvent::class => ['setTagSlug'],
            BeforeEntityPersistedEvent::class => ['setUserSlug'],
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
}