<?php

namespace Sergo\ArtGallery\Repository;

use Doctrine\ORM\EntityRepository;
use Sergo\ArtGallery\Infrastructure\Auth\AdminDataProviderInterface;
use Sergo\ArtGallery\Infrastructure\Auth\AdminDataStorageInterface;
use Sergo\ArtGallery\Exception;

class AdminDoctrineRepository extends EntityRepository implements AdminDataStorageInterface
{
    /**
     * @inheritDoc
     */
    public function findAdminByLogin(string $login): ?AdminDataProviderInterface
    {
        $entities = $this->findBy(['login' => $login]);
        $countEntities = count($entities);

        if ($countEntities > 1) {
            throw new Exception\RuntimeException('Найдены пользователи с дублирующимися логинами');
        }
        return 0 === $countEntities ? null : new AdminDataProvider(current($entities));
    }
}
