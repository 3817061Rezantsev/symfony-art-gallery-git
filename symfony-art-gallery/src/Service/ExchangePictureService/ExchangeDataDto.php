<?php

namespace Sergo\ArtGallery\Service\ExchangePictureService;

class ExchangeDataDto
{
    private int $first_visitor_id;
    private int $second_visitor_id;
    private int $first_picture_id;
    private int $second_picture_id;
    private string $dateOfPurchase;
    private int $cost;
    private string $currency;

    /**
     * @param int $first_visitor_id
     * @param int $second_visitor_id
     * @param int $first_picture_id
     * @param int $second_picture_id
     * @param string $dateOfPurchase
     * @param int $cost
     * @param string $currency
     */
    public function __construct(
        int $first_visitor_id,
        int $second_visitor_id,
        int $first_picture_id,
        int $second_picture_id,
        string $dateOfPurchase,
        int $cost,
        string $currency
    ) {
        $this->first_visitor_id = $first_visitor_id;
        $this->second_visitor_id = $second_visitor_id;
        $this->first_picture_id = $first_picture_id;
        $this->second_picture_id = $second_picture_id;
        $this->dateOfPurchase = $dateOfPurchase;
        $this->cost = $cost;
        $this->currency = $currency;
    }

    /**
     * @return int
     */
    public function getFirstVisitorId(): int
    {
        return $this->first_visitor_id;
    }

    /**
     * @return int
     */
    public function getSecondVisitorId(): int
    {
        return $this->second_visitor_id;
    }

    /**
     * @return int
     */
    public function getFirstPictureId(): int
    {
        return $this->first_picture_id;
    }

    /**
     * @return int
     */
    public function getSecondPictureId(): int
    {
        return $this->second_picture_id;
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