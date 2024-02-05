<?php

namespace Sergo\ArtGallery\Service;

use Sergo\ArtGallery\Entity\Ticket;
use Sergo\ArtGallery\Entity\TicketRepositoryInterface;
use Psr\Log\LoggerInterface;
use Sergo\ArtGallery\Service\SearchTicketsService\GalleryDto;
use Sergo\ArtGallery\Service\SearchTicketsService\SearchTicketsCriteria;
use Sergo\ArtGallery\Service\SearchTicketsService\TicketDto;

/**
 * сервис поиска билета
 */
class SearchTicketsService
{
    /**
     * @var LoggerInterface - Логгер
     */
    private LoggerInterface $logger;
    /**
     * @var TicketRepositoryInterface - интерфейс для работы с сущностью Ticket
     */
    private TicketRepositoryInterface $ticketRepository;

    /**
     * @param LoggerInterface           $logger           - Логгер
     * @param TicketRepositoryInterface $ticketRepository - интерфейс для работы с сущностью Ticket
     */
    public function __construct(LoggerInterface $logger, TicketRepositoryInterface $ticketRepository)
    {
        $this->logger = $logger;
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * Поиск по критериям
     * @param SearchTicketsCriteria $criteria
     * @return array
     */
    public function search(SearchTicketsCriteria $criteria): array
    {
        $entitiesCollection = $this->ticketRepository->findBy($criteria->toArray());
        $dtoCollection = [];
        foreach ($entitiesCollection as $entity) {
            $dtoCollection[] = $this->createDto($entity);
        }
        $this->logger->debug('found Tickets: ' . count($entitiesCollection));
        return $dtoCollection;
    }

    /**
     * Создание DTO
     * @param Ticket $entity
     * @return TicketDto
     */
    private function createDto(Ticket $entity): TicketDto
    {
        $galleryDto = new GalleryDto(
            $entity->getGallery()->getId(),
            $entity->getGallery()->getName(),
            $entity->getGallery()->getAddress()
        );
        return new TicketDto(
            $entity->getId(),
            $galleryDto,
            $entity->getDateOfVisit()->format('Y-m-d'),
            $entity->getCost(),
            $entity->getCurrency(),
        );
    }
}
