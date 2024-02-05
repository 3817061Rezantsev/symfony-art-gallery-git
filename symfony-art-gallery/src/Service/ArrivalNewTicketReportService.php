<?php

namespace Sergo\ArtGallery\Service;

use DateTimeImmutable;
use Exception;
use Sergo\ArtGallery\Entity\CurrencyFactoryInterface;
use Sergo\ArtGallery\Entity\TicketPurchaseReport;
use Sergo\ArtGallery\Entity\TicketPurchaseReportRepositoryInterface;
use Sergo\ArtGallery\Entity\TicketRepositoryInterface;
use Sergo\ArtGallery\Entity\VisitorRepositoryInterface;
use Sergo\ArtGallery\Service\ArrivalNewTicketReportService\NewTicketReportDto;
use Sergo\ArtGallery\Service\ArrivalNewTicketReportService\ResultRegisteringTicketReportDto;
use Sergo\ArtGallery\ValueObject\Money;
use Sergo\ArtGallery\ValueObject\PurchasePrice;

/**
 * сервис для добавления новых актов о покупке билетов
 */
class ArrivalNewTicketReportService
{
    /**
     * @var TicketPurchaseReportRepositoryInterface - реализция репозитория для сущности TicketPurchaseReport.
     */
    private TicketPurchaseReportRepositoryInterface $ticketReportRepository;
    /**
     * @var VisitorRepositoryInterface - интерфейс для работы с сущностью Visitor
     */
    private VisitorRepositoryInterface $visitorRepository;
    /**
     * @var TicketRepositoryInterface - интерфейс для работы с сущностью Ticket
     */
    private TicketRepositoryInterface $ticketRepository;
    /**
     * @var CurrencyFactoryInterface - фабрика для реализации поиска объекта Currency
     */
    private CurrencyFactoryInterface $currencyDbFactory;

    /**
     * @param TicketPurchaseReportRepositoryInterface $ticketReportRepository
     * - реализция репозитория для сущности TicketPurchaseReport.
     * @param VisitorRepositoryInterface $visitorRepository
     * - интерфейс для работы с сущностью Visitor
     * @param TicketRepositoryInterface $ticketRepository
     * - интерфейс для работы с сущностью Ticket
     * @param CurrencyFactoryInterface $currencyDbFactory
     * - фабрика для реализации поиска объекта Currency
     */
    public function __construct(
        TicketPurchaseReportRepositoryInterface $ticketReportRepository,
        VisitorRepositoryInterface $visitorRepository,
        TicketRepositoryInterface $ticketRepository,
        CurrencyFactoryInterface $currencyDbFactory
    ) {
        $this->ticketReportRepository = $ticketReportRepository;
        $this->visitorRepository = $visitorRepository;
        $this->ticketRepository = $ticketRepository;
        $this->currencyDbFactory = $currencyDbFactory;
    }

    /**
     * добавление сущности
     * @param NewTicketReportDto $newTicketReportDto
     * @return ResultRegisteringTicketReportDto
     */
    public function registerTicketReport(NewTicketReportDto $newTicketReportDto): ResultRegisteringTicketReportDto
    {
        $visitorArr = $this->visitorRepository->findBy(['id' => $newTicketReportDto->getVisitorId()]);
        $visitor = $visitorArr[0];
        $ticketArr = $this->ticketRepository->findBy(['id' => $newTicketReportDto->getTicketId()]);
        $ticket = $ticketArr[0];
        $date = DateTimeImmutable::createFromFormat('Y-m-d H:i', $newTicketReportDto->getDateOfPurchase());
        $purchasePrice = new PurchasePrice(
            $date,
            new Money(
                $ticket->getCost(),
                $this->currencyDbFactory->findByName($ticket->getCurrency())
            )
        );
        $entity = new TicketPurchaseReport(
            $this->ticketReportRepository->nextId(),
            $visitor,
            $ticket,
            $purchasePrice
        );
        $this->ticketReportRepository->add($entity);
        return new ResultRegisteringTicketReportDto($entity->getId());
    }
}
