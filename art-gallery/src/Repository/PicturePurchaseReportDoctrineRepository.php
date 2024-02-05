<?php

namespace Sergo\ArtGallery\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Sergo\ArtGallery\Entity\PicturePurchaseReport;
use Sergo\ArtGallery\Entity\PicturePurchaseReportRepositoryInterface;

class PicturePurchaseReportDoctrineRepository extends EntityRepository implements
    PicturePurchaseReportRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('ppr')
            ->from(PicturePurchaseReport::class, 'ppr')
            ->join('ppr.picture', 'pic')
            ->join('ppr.currency', 'c')
            ->join('pic.painter', 'p')
            ->join('ppr.visitor', 'v');
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
            if (0 === strpos($criteriaName, 'picture_')) {
                $preparedName = substr($criteriaName, 8);
                if (0 === strpos($preparedName, 'painter_')) {
                    $preparedName = substr($preparedName, 8);
                    $whereExprAnd->add($queryBuilder->expr()->eq("p.$preparedName", ":$criteriaName"));
                } else {
                    $whereExprAnd->add($queryBuilder->expr()->eq("pic.$preparedName", ":$criteriaName"));
                }
            } elseif (0 === strpos($criteriaName, 'visitor_')) {
                $preparedName = substr($criteriaName, 8);
                $whereExprAnd->add($queryBuilder->expr()->eq("v.$preparedName", ":$criteriaName"));
            } elseif ('currency' === $criteriaName) {
                $whereExprAnd->add($queryBuilder->expr()->eq("c.name", ":$criteriaName"));
            } else {
                $whereExprAnd->add($queryBuilder->expr()->eq("ppr.$criteriaName", ":$criteriaName"));
            }
        }
        $queryBuilder->where($whereExprAnd);
        $queryBuilder->setParameters($criteria);
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
    public function add(PicturePurchaseReport $entity): PicturePurchaseReport
    {
        $this->getEntityManager()->persist($entity);
        return $entity;
    }
}
