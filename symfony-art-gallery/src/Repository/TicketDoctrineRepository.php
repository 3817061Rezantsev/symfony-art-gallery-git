<?php

namespace Sergo\ArtGallery\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Sergo\ArtGallery\Entity\Ticket;
use Sergo\ArtGallery\Entity\TicketRepositoryInterface;

class TicketDoctrineRepository extends EntityRepository implements TicketRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select(['t', 'g'])
            ->from(Ticket::class, 't')
            ->join('t.currency', 'c')
            ->join('t.gallery', 'g');
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
            if (0 === strpos($criteriaName, 'gallery_')) {
                $preparedName = substr($criteriaName, 8);
                $whereExprAnd->add($queryBuilder->expr()->eq("g.$preparedName", ":$criteriaName"));
            } elseif ('currency' === $criteriaName) {
                $whereExprAnd->add($queryBuilder->expr()->eq("c.name", ":$criteriaName"));
            } else {
                $whereExprAnd->add($queryBuilder->expr()->eq("t.$criteriaName", ":$criteriaName"));
            }
        }
        $queryBuilder->where($whereExprAnd);
        $queryBuilder->setParameters($criteria);
    }
}
