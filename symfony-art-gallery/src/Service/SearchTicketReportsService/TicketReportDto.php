<?php

namespace Sergo\ArtGallery\Service\SearchTicketReportsService;

class TicketReportDto
{
    /**
     * Номер отчета
     *
     * @var int
     */
    private int $id;

    /**
     * Номер посетителя
     *
     * @var VisitorDto
     */
    private VisitorDto $visitor;

    /**
     * Номер билета
     *
     * @var TicketDto
     */
    private TicketDto $ticket;
    private string $currency;
    private string $dateOfPurchase;
    private int $cost;

    /**
     * @param int $id
     * @param VisitorDto $visitor
     * @param TicketDto $ticket
     * @param string $currency
     * @param string $dateOfPurchase
     * @param int $cost
     */
    public function __construct(
        int $id,
        VisitorDto $visitor,
        TicketDto $ticket,
        string $currency,
        string $dateOfPurchase,
        int $cost
    ) {
        $this->id = $id;
        $this->visitor = $visitor;
        $this->ticket = $ticket;
        $this->currency = $currency;
        $this->dateOfPurchase = $dateOfPurchase;
        $this->cost = $cost;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return VisitorDto
     */
    public function getVisitor(): VisitorDto
    {
        return $this->visitor;
    }

    /**
     * @return TicketDto
     */
    public function getTicket(): TicketDto
    {
        return $this->ticket;
    }
    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getDateOfPurchase(): string
    {
        return $this->dateOfPurchase;
    }

    /**
     * @return int
     */
    public function getCost(): int
    {
        return $this->cost;
    }
}
