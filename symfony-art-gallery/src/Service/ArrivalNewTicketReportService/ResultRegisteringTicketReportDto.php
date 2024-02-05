<?php

namespace Sergo\ArtGallery\Service\ArrivalNewTicketReportService;

class ResultRegisteringTicketReportDto
{
    private int $id;

    /**
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
