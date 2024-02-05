<?php

namespace Sergo\ArtGallery\Service\SearchTicketsService;

class TicketDto
{
    /**
     * Установить ID билета
     *
     * @var int
     */
    private int $id;
    /**
     * Установить номер галереи билета
     *
     * @var GalleryDto
     */
    private GalleryDto $gallery;
    /**
     * Установить дату посещения
     *
     * @var string
     */
    private string $dateOfVisit;

    private int $cost;

    private string $currency;

    /**
     * @param int $id
     * @param GalleryDto $gallery
     * @param string $dateOfVisit
     * @param int $cost
     * @param string $currency
     */
    public function __construct(int $id, GalleryDto $gallery, string $dateOfVisit, int $cost, string $currency)
    {
        $this->id = $id;
        $this->gallery = $gallery;
        $this->dateOfVisit = $dateOfVisit;
        $this->cost = $cost;
        $this->currency = $currency;
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


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return GalleryDto
     */
    public function getGallery(): GalleryDto
    {
        return $this->gallery;
    }

    /**
     * @return string
     */
    public function getDateOfVisit(): string
    {
        return $this->dateOfVisit;
    }
}
