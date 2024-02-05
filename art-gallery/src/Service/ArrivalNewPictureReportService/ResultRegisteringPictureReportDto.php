<?php

namespace Sergo\ArtGallery\Service\ArrivalNewPictureReportService;

/**
 * возвращаемый результат
 */
class ResultRegisteringPictureReportDto
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
