<?php

namespace Sergo\ArtGallery\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Sergo\ArtGallery\Entity\Picture;
use Sergo\ArtGallery\Entity\PictureRepositoryInterface;

class PictureDoctrineRepository extends EntityRepository implements PictureRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select(['pic', 'p'])
            ->from(Picture::class, 'pic')
            ->join('pic.painter', 'p');
        $this->buildWhere($queryBuilder, $criteria);
        return $queryBuilder->getQuery()->getResult();
    }

    private function buildWhere(QueryBuilder $queryBuilder, array $criteria): void
    {
        if (0 === count($criteria)) {
            return;
        }
        $whereExprAnd = $queryBuilder->expr()->andX();
        foreach ($criteria as $criteriaName => $criteriaValue) {
            if (array_key_exists('start', $criteria) && array_key_exists('end', $criteria)) {
                $whereExprAnd->add($queryBuilder->expr()->between("pic.year", ":start", ":end"));
                break;
            }
            if (0 === strpos($criteriaName, 'painter_')) {
                $preparedName = substr($criteriaName, 8);
                $whereExprAnd->add($queryBuilder->expr()->eq("p.$preparedName", ":$criteriaName"));
            } else {
                $whereExprAnd->add($queryBuilder->expr()->eq("pic.$criteriaName", ":$criteriaName"));
            }
        }
        $queryBuilder->where($whereExprAnd);
        $queryBuilder->setParameters($criteria);
    }

    /**
     * @inheritDoc
     */
    public function add(Picture $picture): Picture
    {
        $this->getEntityManager()->persist($picture);
        return $picture;
    }

    /**
     * @inheritDoc
     */
    public function nextId(): int
    {
        return $this->getClassMetadata()->idGenerator->generateId($this->getEntityManager(), null);
    }
}
