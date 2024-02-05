<?php

namespace Sergo\ArtGallery\Security;

use Doctrine\ORM\EntityManagerInterface;
use Sergo\ArtGallery\Entity\AdminRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;


class UserProvider implements UserProviderInterface
{
    /**
     * @var EntityManagerInterface - менеджер сущностей
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var AdminRepositoryInterface - сервис поиска админов
     */
    private AdminRepositoryInterface $adminRepository;

    /**
     * @param EntityManagerInterface   $entityManager
     * @param AdminRepositoryInterface $adminRepository
     */
    public function __construct(EntityManagerInterface $entityManager, AdminRepositoryInterface $adminRepository)
    {
        $this->entityManager = $entityManager;
        $this->adminRepository = $adminRepository;
    }

    /**
     * @inheritDoc
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        $userFromDomain = $this->adminRepository->findAdminByLogin($user->getUserIdentifier());
        $this->entityManager->refresh($userFromDomain);
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function supportsClass(string $class): bool
    {
        return $class === User::class;
    }

    /**
     * @inheritDoc
     */
    public function loadUserByUsername(string $username): User
    {
        return $this->loadUserByIdentifier($username);
    }

    public function loadUserByIdentifier(string $identifier): User
    {
        $userFromDomain = $this->adminRepository->findAdminByLogin($identifier);
        if (null === $userFromDomain) {
            throw new UserNotFoundException("Пользователь $identifier не найден");
        }
        return new User($userFromDomain);
    }
}