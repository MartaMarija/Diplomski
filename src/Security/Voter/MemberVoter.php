<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MemberVoter extends Voter
{
    public const MEMBER = 'member';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (self::MEMBER !== $attribute) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (empty($user) || $user->getUserIdentifier() !== $subject->getEmail()) {
            return false;
        }

        return true;
    }
}
