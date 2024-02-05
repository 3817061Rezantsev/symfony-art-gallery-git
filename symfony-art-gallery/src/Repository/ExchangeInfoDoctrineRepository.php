<?php

namespace Sergo\ArtGallery\Repository;

use Doctrine\ORM\EntityRepository;
use Sergo\ArtGallery\Entity\ExchangeInfo;

class ExchangeInfoDoctrineRepository extends EntityRepository implements
    \Sergo\ArtGallery\Entity\ExchangeInfoRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @inheritDoc
     */
    public function nextId(): int
    {
        return $this->getClassMetadata()->idGenerator->generateId($this->getEntityManager(), null);
    }

    /**
     * @inheritDoc
     */
    public function add(ExchangeInfo $entity): ExchangeInfo
    {
        $this->getEntityManager()->persist($entity);
        return $entity;
    }
}