<?php

namespace Sergo\ArtGallery\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Sergo\ArtGallery\Entity\TicketPurchaseReport;
use Sergo\ArtGallery\Entity\TicketPurchaseReportRepositoryInterface;

class TicketPurchaseReportDoctrineRepository extends EntityRepository implements
    TicketPurchaseReportRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('tpr')
            ->from(TicketPurchaseReport::class, 'tpr')
            ->join('tpr.ticket', 't')
            ->join('t.currency', 'c')
            ->join('t.gallery', 'g')
            ->join('tpr.visitor', 'v');
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
            if (0 === strpos($criteriaName, 'ticket_')) {
                $preparedName = substr($criteriaName, 7);
                if (0 === strpos($preparedName, 'gallery_')) {
                    $preparedName = substr($preparedName, 8);
                    $whereExprAnd->add($queryBuilder->expr()->eq("g.$preparedName", ":$criteriaName"));
                } elseif ('currency' === $criteriaName) {
                    $whereExprAnd->add($queryBuilder->expr()->eq("c.name", ":$criteriaName"));
                } else {
                    $whereExprAnd->add($queryBuilder->expr()->eq("t.$preparedName", ":$criteriaName"));
                }
            } elseif (0 === strpos($criteriaName, 'visitor_')) {
                $preparedName = substr($criteriaName, 8);
                $whereExprAnd->add($queryBuilder->expr()->eq("v.$preparedName", ":$criteriaName"));
            } elseif ('currency' === $criteriaName) {
                $whereExprAnd->add($queryBuilder->expr()->eq("c.name", ":$criteriaName"));
            } else {
                $whereExprAnd->add($queryBuilder->expr()->eq("tpr.$criteriaName", ":$criteriaName"));
            }
        }
        $queryBuilder->where($whereExprAnd);
        $queryBuilder->setParameters($criteria);
    }

    /**
     * @inheritDoc
     */
    public function add(TicketPurchaseReport $entity): TicketPurchaseReport
    {
        $this->getEntityManager()->persist($entity);
        return $entity;
    }

    /**
     * @inheritDoc
     */
    public function nextId(): int
    {
        return $this->getClassMetadata()->idGenerator->generateId($this->getEntityManager(), null);
    }
}
