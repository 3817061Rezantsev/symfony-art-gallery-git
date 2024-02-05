<?php

namespace Sergo\ArtGallery\Repository;

use Doctrine\ORM\EntityRepository;
use Sergo\ArtGallery\Entity\PainterRepositoryInterface;

class PainterDoctrineRepository extends EntityRepository implements PainterRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }
}
