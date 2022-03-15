<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserVoter extends Voter
{
    public const PANEL_VIEW = 'PANEL_VIEW';

    protected function supports(string $attribute, $user): bool
    {
        return in_array($attribute, [self::PANEL_VIEW])
            && $user instanceof \App\Entity\User;
    }

    protected function voteOnAttribute(string $attribute, $user, TokenInterface $token): bool
    {
        $sessionUser = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$sessionUser instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::PANEL_VIEW:
                return $this->canView($user, $token);
                break;
        }

        return false;
    }

    // if the user matche the event user
    private function canView(User $user, TokenInterface $token)
    {
        return $user === $token->getUser();
    }
}
