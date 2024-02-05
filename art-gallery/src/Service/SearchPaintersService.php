<?php

namespace Sergo\ArtGallery\Service;

use Sergo\ArtGallery\Entity\Painter;
use Sergo\ArtGallery\Entity\PainterRepositoryInterface;
use Psr\Log\LoggerInterface;
use Sergo\ArtGallery\Service\SearchPaintersService\PainterDto;
use Sergo\ArtGallery\Service\SearchPaintersService\SearchPainterCriteria;

/**
 * сервис поиска художника
 */
class SearchPaintersService
{
    /**
     * Логгер
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var PainterRepositoryInterface - интерфейс для работы с сущностью Painter
     */
    private PainterRepositoryInterface $painterRepository;

    /**
     * @param LoggerInterface            $logger            - Логгер
     * @param PainterRepositoryInterface $painterRepository - интерфейс для работы с сущностью Painter
     */
    public function __construct(LoggerInterface $logger, PainterRepositoryInterface $painterRepository)
    {
        $this->logger = $logger;
        $this->painterRepository = $painterRepository;
    }

    /**
     * Поиск по критериям
     * @param SearchPainterCriteria $criteria
     * @return array
     */
    public function search(SearchPainterCriteria $criteria): array
    {
        $entitiesCollection = $this->painterRepository->findBy($criteria->toArray());
        $dtoCollection = [];
        foreach ($entitiesCollection as $entity) {
            $dtoCollection[] = $this->createDto($entity);
        }
        $this->logger->debug('found Painters: ' . count($entitiesCollection));
        return $dtoCollection;
    }

    /**
     * Создание DTO
     * @param Painter $entity
     * @return PainterDto
     */
    private function createDto(Painter $entity): PainterDto
    {
        $date = null === $entity->getDateOfBirth() ? null : $entity->getDateOfBirth()->format('Y.m.d');
        return new PainterDto(
            $entity->getId(),
            $entity->getFullName(),
            $date,
            $entity->getTelephoneNumber(),
        );
    }
}
