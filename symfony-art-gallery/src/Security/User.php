<?php

namespace Sergo\ArtGallery\Security;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Sergo\ArtGallery\Entity\Admin as UserFromDomain;

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * пользователь из приложения
     * @var UserFromDomain
     */
    private UserFromDomain $user;

    /**
     * @param UserFromDomain $user - пользователь из приложения
     */
    public function __construct(UserFromDomain $user)
    {
        $this->user = $user;
    }

    /**
     * @inheritDoc
     */
    public function getRoles(): array
    {
        return [
            UserRoleInterface::ROLE_AUTH_USER
        ];
    }

    /**
     * @inheritDoc
     */
    public function getPassword(): ?string
    {
        return $this->user->getPassword();
    }

    /**
     * @inheritDoc
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @inheritDoc
     */
    public function getUsername(): string
    {
        return $this->user->getLogin();
    }

    public function getUserIdentifier(): string
    {
        return $this->user->getLogin();
    }
}
