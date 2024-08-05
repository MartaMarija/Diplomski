<?php

namespace App\Model;

use Pimcore\Model\DataObject\ClassDefinition\Data\Password;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class Member extends \Pimcore\Model\DataObject\Member implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
        /** @var Password $field */
        $field = $this->getClass()->getFieldDefinition('password');
        $field->getDataForResource($this->getPassword(), $this);
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }
}
