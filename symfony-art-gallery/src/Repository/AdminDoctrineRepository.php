<?php

namespace Sergo\ArtGallery\Repository;

use Doctrine\ORM\EntityRepository;
use Sergo\ArtGallery\Entity\Admin;
use Sergo\ArtGallery\Entity\AdminRepositoryInterface;
use Sergo\ArtGallery\Exception;

class AdminDoctrineRepository extends EntityRepository implements AdminRepositoryInterface
{
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @inheritDoc
     */
    public function findAdminByLogin(string $login): ?Admin
    {
        $entities = $this->findBy(['login' => $login]);
        $countEntities = count($entities);

        if ($countEntities > 1) {
            throw new Exception\RuntimeException('Найдены пользователи с дублирующимися логинами');
        }
        return 0 === $countEntities ? null : (current($entities));
    }
}
