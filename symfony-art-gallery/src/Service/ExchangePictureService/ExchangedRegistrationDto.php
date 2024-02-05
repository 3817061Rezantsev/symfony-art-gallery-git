<?php

namespace Sergo\ArtGallery\Service\ExchangePictureService;

class ExchangedRegistrationDto
{
    private int $firstId;
    private int $secondId;

    /**
     * @param int $firstId
     * @param int $secondId
     */
    public function __construct(int $firstId, int $secondId)
    {
        $this->firstId = $firstId;
        $this->secondId = $secondId;
    }

    /**
     * @return int
     */
    public function getFirstId(): int
    {
        return $this->firstId;
    }

    /**
     * @return int
     */
    public function getSecondId(): int
    {
        return $this->secondId;
    }


}