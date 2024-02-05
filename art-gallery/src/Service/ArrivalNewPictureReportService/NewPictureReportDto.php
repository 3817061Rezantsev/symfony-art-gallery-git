<?php

namespace Sergo\ArtGallery\Service\ArrivalNewPictureReportService;

/**
 * данные, необходимые для создания акта о покупке картины
 */
class NewPictureReportDto
{
    private int $visitorId;
    private int $pictureId;
    private string $dateOfPurchase;
    private int $cost;
    private string $currency;

    /**
     * @param int $visitorId
     * @param int $pictureId
     * @param string $dateOfPurchase
     * @param int $cost
     * @param string $currency
     */
    public function __construct(int $visitorId, int $pictureId, string $dateOfPurchase, int $cost, string $currency)
    {
        $this->visitorId = $visitorId;
        $this->pictureId = $pictureId;
        $this->dateOfPurchase = $dateOfPurchase;
        $this->cost = $cost;
        $this->currency = $currency;
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
    public function getPictureId(): int
    {
        return $this->pictureId;
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

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }
}
