<?php

namespace Sergo\ArtGallery\Repository;

use Doctrine\ORM\EntityRepository;
use Sergo\ArtGallery\Entity\VisitorRepositoryInterface;

class VisitorDoctrineRepository extends EntityRepository implements VisitorRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }
}
