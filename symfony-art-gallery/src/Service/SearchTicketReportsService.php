<?php

namespace Sergo\ArtGallery\Service;

use Sergo\ArtGallery\Entity\TicketPurchaseReport;
use Sergo\ArtGallery\Entity\TicketPurchaseReportRepositoryInterface;
use Psr\Log\LoggerInterface;
use Sergo\ArtGallery\Service\SearchTicketReportsService\GalleryDto;
use Sergo\ArtGallery\Service\SearchTicketReportsService\SearchTicketReportCriteria;
use Sergo\ArtGallery\Service\SearchTicketReportsService\TicketDto;
use Sergo\ArtGallery\Service\SearchTicketReportsService\VisitorDto;

/**
 * сервис поиска акта о покупке билета
 */
class SearchTicketReportsService
{
    /**
     * Логгер
     * @var LoggerInterface
     */
    private LoggerInterface $logger;


    /**
     * @var TicketPurchaseReportRepositoryInterface - интерфейс для работы с сущностью TicketPurchaseReport
     */
    private TicketPurchaseReportRepositoryInterface $ticketReportRepository;

    /**
     * @param LoggerInterface                         $logger                 - Логгер
     * @param TicketPurchaseReportRepositoryInterface $ticketReportRepository
     * - интерфейс для работы с сущностью TicketPurchaseReport
     */
    public function __construct(
        LoggerInterface $logger,
        TicketPurchaseReportRepositoryInterface $ticketReportRepository
    ) {
        $this->logger = $logger;
        $this->ticketReportRepository = $ticketReportRepository;
    }

    /**
     * Поиск по критериям
     * @param SearchTicketReportCriteria $searchCriteria
     * @return array
     */
    public function search(SearchTicketReportCriteria $searchCriteria): array
    {
        $entitiesCollection = $this->ticketReportRepository->findBy($searchCriteria->toArray());
        $dtoCollection = [];
        foreach ($entitiesCollection as $entity) {
            $dtoCollection[] = $this->createDto($entity);
        }
        $this->logger->debug('found text document: ' . count($entitiesCollection));
        return $dtoCollection;
    }

    /**
     * Создание DTO
     * @param TicketPurchaseReport $entity
     * @return SearchTicketReportsService\TicketReportDto
     */
    private function createDto(TicketPurchaseReport $entity): SearchTicketReportsService\TicketReportDto
    {
        $galleryDto = new GalleryDto(
            $entity->getTicket()->getGallery()->getId(),
            $entity->getTicket()->getGallery()->getName(),
            $entity->getTicket()->getGallery()->getAddress()
        );
        $ticketDto = new TicketDto(
            $entity->getTicket()->getId(),
            $galleryDto,
            $entity->getTicket()->getDateOfVisit()->format('Y.m.d'),
            $entity->getTicket()->getCost(),
            $entity->getTicket()->getCurrency(),
        );
        $visitorDto = new VisitorDto(
            $entity->getVisitor()->getId(),
            $entity->getVisitor()->getFullName(),
            $entity->getVisitor()->getDateOfBirth()->format('Y.m.d'),
            $entity->getVisitor()->getTelephoneNumber(),
        );
        return new SearchTicketReportsService\TicketReportDto(
            $entity->getId(),
            $visitorDto,
            $ticketDto,
            $entity->getPurchasePrice()->getMoney()->getCurrency()->getName(),
            $entity->getPurchasePrice()->getDate()->format('Y-m-d H:i'),
            $entity->getPurchasePrice()->getMoney()->getAmount()
        );
    }
}
