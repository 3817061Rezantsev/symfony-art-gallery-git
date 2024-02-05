<?php

namespace Sergo\ArtGallery\Service\SearchTicketsService;

class SearchTicketsCriteria
{
    private ?int $id = null;
    /**
     * Номер галереи
     *
     * @var int|null
     */
    private ?int $galleryId = null;

    /**
     * Имя галереи
     *
     * @var string|null
     */
    private ?string $galleryName = null;

    /**
     * Адрес галереи
     *
     * @var string|null
     */
    private ?string $galleryAddress = null;
    /**
     * Установить дату посещения
     *
     * @var string|null
     */
    private ?string $dateOfVisit = null;

    private ?int $cost = null;

    private ?string $currency = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return SearchTicketsCriteria
     */
    public function setId(?int $id): SearchTicketsCriteria
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getGalleryId(): ?int
    {
        return $this->galleryId;
    }

    /**
     * @param int|null $galleryId
     * @return SearchTicketsCriteria
     */
    public function setGalleryId(?int $galleryId): SearchTicketsCriteria
    {
        $this->galleryId = $galleryId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGalleryName(): ?string
    {
        return $this->galleryName;
    }

    /**
     * @param string|null $galleryName
     * @return SearchTicketsCriteria
     */
    public function setGalleryName(?string $galleryName): SearchTicketsCriteria
    {
        $this->galleryName = $galleryName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getGalleryAddress(): ?string
    {
        return $this->galleryAddress;
    }

    /**
     * @param string|null $galleryAddress
     * @return SearchTicketsCriteria
     */
    public function setGalleryAddress(?string $galleryAddress): SearchTicketsCriteria
    {
        $this->galleryAddress = $galleryAddress;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDateOfVisit(): ?string
    {
        return $this->dateOfVisit;
    }

    /**
     * @param string|null $dateOfVisit
     * @return SearchTicketsCriteria
     */
    public function setDateOfVisit(?string $dateOfVisit): SearchTicketsCriteria
    {
        $this->dateOfVisit = $dateOfVisit;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCost(): ?int
    {
        return $this->cost;
    }

    /**
     * @param int|null $cost
     * @return SearchTicketsCriteria
     */
    public function setCost(?int $cost): SearchTicketsCriteria
    {
        $this->cost = $cost;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param string|null $currency
     * @return SearchTicketsCriteria
     */
    public function setCurrency(?string $currency): SearchTicketsCriteria
    {
        $this->currency = $currency;
        return $this;
    }

    public function toArray(): array
    {
        $criteria = [
            'id' => $this->getId(),
            'dateOfVisit' => $this->getDateOfVisit(),
            'gallery_name' => $this->getGalleryName(),
            'gallery_address' => $this->getGalleryAddress(),
            'gallery_id' => $this->getGalleryId(),
            'cost' =>  $this->getCost(),
            'currency' =>  $this->getCurrency(),
        ];
        return array_filter($criteria, static function ($v): bool {
            return null !== $v;
        });
    }
}
