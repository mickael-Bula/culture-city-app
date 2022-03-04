<?php

namespace App\Security\Voter;

use App\Entity\Event;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class EventVoter extends Voter
{
    public const EVENT_EDIT = 'EVENT_EDIT';

    protected function supports(string $attribute, $event): bool
    {
        return in_array($attribute, [self::EVENT_EDIT])
            && $event instanceof \App\Entity\Event;
    }

    protected function voteOnAttribute(string $attribute, $event, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // if the event has an advertiser
        if(null === $event->getUser())
        {
            return false;
        }

        switch ($attribute) {
            case self::EVENT_EDIT:
                return $this->canEdit($event, $user);
                break;
        }

        return false;
    }

    // if the user matche the event user
    private function canEdit(Event $event, User $user)
    {
        return $user === $event->getUser();
    }
}
