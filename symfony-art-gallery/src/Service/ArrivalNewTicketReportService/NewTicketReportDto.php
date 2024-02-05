<?php

namespace Sergo\ArtGallery\Service\ArrivalNewTicketReportService;

class NewTicketReportDto
{
    private int $visitorId;
    private int $ticketId;
    private string $dateOfPurchase;

    /**
     * @param int $visitorId
     * @param int $ticketId
     * @param string $dateOfPurchase
     */
    public function __construct(int $visitorId, int $ticketId, string $dateOfPurchase)
    {
        $this->visitorId = $visitorId;
        $this->ticketId = $ticketId;
        $this->dateOfPurchase = $dateOfPurchase;
    }

    /**
     * @return int
     */
    public function getVisitorId(): int
    {
        return $this->visitorId;
    }

    /**
     * @return int
     */
    public function getTicketId(): int
    {
        return $this->ticketId;
    }

    /**
     * @return string
     */
    public function getDateOfPurchase(): string
    {
        return $this->dateOfPurchase;
    }
}
