<?php

namespace Sergo\ArtGallery\Service;

use Sergo\ArtGallery\Entity\Visitor;
use Sergo\ArtGallery\Entity\VisitorRepositoryInterface;
use Psr\Log\LoggerInterface;
use Sergo\ArtGallery\Service\SearchVisitorsService\SearchVisitorCriteria;
use Sergo\ArtGallery\Service\SearchVisitorsService\VisitorDto;

class SearchVisitorsService
{
    /**
     * @var LoggerInterface - Логгер
     */
    private LoggerInterface $logger;
    /**
     * @var VisitorRepositoryInterface - интерфейс для работы с сущностью Visitor
     */
    private VisitorRepositoryInterface $visitorRepository;

    /**
     * @param LoggerInterface            $logger            - Логгер
     * @param VisitorRepositoryInterface $visitorRepository - интерфейс для работы с сущностью Visitor
     */
    public function __construct(LoggerInterface $logger, VisitorRepositoryInterface $visitorRepository)
    {
        $this->logger = $logger;
        $this->visitorRepository = $visitorRepository;
    }

    /**
     * Поиск по критериям
     * @param SearchVisitorCriteria $criteria
     * @return array
     */
    public function search(SearchVisitorCriteria $criteria): array
    {
        $entitiesCollection = $this->visitorRepository->findBy($criteria->toArray());
        $dtoCollection = [];
        foreach ($entitiesCollection as $entity) {
            $dtoCollection[] = $this->createDto($entity);
        }
        $this->logger->debug('found Visitors: ' . count($entitiesCollection));
        return $dtoCollection;
    }

    /**
     * Создание DTO
     * @param Visitor $entity
     * @return VisitorDto
     */
    private function createDto(Visitor $entity): VisitorDto
    {
        return new VisitorDto(
            $entity->getId(),
            $entity->getFullName(),
            $entity->getDateOfBirth()->format('Y-m-d'),
            $entity->getTelephoneNumber(),
        );
    }
}
