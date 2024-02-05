<?php

namespace Sergo\ArtGallery\Service\SearchTicketReportsService;

class SearchTicketReportCriteria
{
    /**
     * Номер отчета
     *
     * @var int|null
     */
    private ?int $id = null;
    /**
     * ID пользователя
     *
     * @var int|null
     */
    private ?int $visitorId = null;
    /**
     * Имя пользователя
     *
     * @var string|null
     */
    private ?string $visitorFullName = null;

    /**
     * Дата рождения пользователя
     *
     * @var string|null
     */
    private ?string $visitorDateOfBirth = null;

    /**
     * Номер телефона пользователя
     *
     * @var string|null
     */
    private ?string $visitorTelephoneNumber = null;

    /**
     * Установить ID билета
     *
     * @var int|null
     */
    private ?int $ticketId = null;
    /**
     * Номер галереи
     *
     * @var int|null
     */
    private ?int $ticketGalleryId = null;

    /**
     * Имя галереи
     *
     * @var string|null
     */
    private ?string $ticketGalleryName = null;

    /**
     * Адрес галереи
     *
     * @var string|null
     */
    private ?string $ticketGalleryAddress = null;
    /**
     * Установить дату посещения
     *
     * @var string|null
     */
    private ?string $ticketDateOfVisit = null;

    private ?string $dateOfPurchase = null;

    private ?int $cost = null;

    private ?string $currency = null;

    private ?int $ticketCost = null;

    private ?string $ticketCurrency = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return SearchTicketReportCriteria
     */
    public function setId(?int $id): SearchTicketReportCriteria
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getVisitorId(): ?int
    {
        return $this->visitorId;
    }

    /**
     * @param int|null $visitorId
     * @return SearchTicketReportCriteria
     */
    public function setVisitorId(?int $visitorId): SearchTicketReportCriteria
    {
        $this->visitorId = $visitorId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVisitorFullName(): ?string
    {
        return $this->visitorFullName;
    }

    /**
     * @param string|null $visitorFullName
     * @return SearchTicketReportCriteria
     */
    public function setVisitorFullName(?string $visitorFullName): SearchTicketReportCriteria
    {
        $this->visitorFullName = $visitorFullName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVisitorDateOfBirth(): ?string
    {
        return $this->visitorDateOfBirth;
    }

    /**
     * @param string|null $visitorDateOfBirth
     * @return SearchTicketReportCriteria
     */
    public function setVisitorDateOfBirth(?string $visitorDateOfBirth): SearchTicketReportCriteria
    {
        $this->visitorDateOfBirth = $visitorDateOfBirth;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVisitorTelephoneNumber(): ?string
    {
        return $this->visitorTelephoneNumber;
    }

    /**
     * @param string|null $visitorTelephoneNumber
     * @return SearchTicketReportCriteria
     */
    public function setVisitorTelephoneNumber(?string $visitorTelephoneNumber): SearchTicketReportCriteria
    {
        $this->visitorTelephoneNumber = $visitorTelephoneNumber;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getTicketId(): ?int
    {
        return $this->ticketId;
    }

    /**
     * @param int|null $ticketId
     * @return SearchTicketReportCriteria
     */
    public function setTicketId(?int $ticketId): SearchTicketReportCriteria
    {
        $this->ticketId = $ticketId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getTicketGalleryId(): ?int
    {
        return $this->ticketGalleryId;
    }

    /**
     * @param int|null $ticketGalleryId
     * @return SearchTicketReportCriteria
     */
    public function setTicketGalleryId(?int $ticketGalleryId): SearchTicketReportCriteria
    {
        $this->ticketGalleryId = $ticketGalleryId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTicketGalleryName(): ?string
    {
        return $this->ticketGalleryName;
    }

    /**
     * @param string|null $ticketGalleryName
     * @return SearchTicketReportCriteria
     */
    public function setTicketGalleryName(?string $ticketGalleryName): SearchTicketReportCriteria
    {
        $this->ticketGalleryName = $ticketGalleryName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTicketGalleryAddress(): ?string
    {
        return $this->ticketGalleryAddress;
    }

    /**
     * @param string|null $ticketGalleryAddress
     * @return SearchTicketReportCriteria
     */
    public function setTicketGalleryAddress(?string $ticketGalleryAddress): SearchTicketReportCriteria
    {
        $this->ticketGalleryAddress = $ticketGalleryAddress;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTicketDateOfVisit(): ?string
    {
        return $this->ticketDateOfVisit;
    }

    /**
     * @param string|null $ticketDateOfVisit
     * @return SearchTicketReportCriteria
     */
    public function setTicketDateOfVisit(?string $ticketDateOfVisit): SearchTicketReportCriteria
    {
        $this->ticketDateOfVisit = $ticketDateOfVisit;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDateOfPurchase(): ?string
    {
        return $this->dateOfPurchase;
    }

    /**
     * @param string|null $dateOfPurchase
     * @return SearchTicketReportCriteria
     */
    public function setDateOfPurchase(?string $dateOfPurchase): SearchTicketReportCriteria
    {
        $this->dateOfPurchase = $dateOfPurchase;
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
     * @return SearchTicketReportCriteria
     */
    public function setCost(?int $cost): SearchTicketReportCriteria
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
     * @return SearchTicketReportCriteria
     */
    public function setCurrency(?string $currency): SearchTicketReportCriteria
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getTicketCost(): ?int
    {
        return $this->ticketCost;
    }

    /**
     * @param int|null $ticketCost
     * @return SearchTicketReportCriteria
     */
    public function setTicketCost(?int $ticketCost): SearchTicketReportCriteria
    {
        $this->ticketCost = $ticketCost;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTicketCurrency(): ?string
    {
        return $this->ticketCurrency;
    }

    /**
     * @param string|null $ticketCurrency
     * @return SearchTicketReportCriteria
     */
    public function setTicketCurrency(?string $ticketCurrency): SearchTicketReportCriteria
    {
        $this->ticketCurrency = $ticketCurrency;
        return $this;
    }



    public function toArray(): array
    {
        $criteria = [
            'id' => $this->getId(),
            'ticket_id' =>  $this->getTicketId(),
            'visitor_id' =>  $this->getVisitorId(),
            'visitor_fullName' =>  $this->getVisitorFullName(),
            'visitor_dateOfBirth' =>  $this->getVisitorDateOfBirth(),
            'visitor_telephoneNumber' =>  $this->getVisitorTelephoneNumber(),
            'ticket_dateOfVisit' => $this->getTicketDateOfVisit(),
            'ticket_cost' => $this->getTicketCost(),
            'ticket_currency' => $this->getTicketCurrency(),
            'ticket_gallery_name' => $this->getTicketGalleryName(),
            'ticket_gallery_address' => $this->getTicketGalleryAddress(),
            'ticket_gallery_id' => $this->getTicketGalleryId(),
            'cost' =>  $this->getCost(),
            'currency' =>  $this->getCurrency(),
            'dateOfPurchase' =>  $this->getDateOfPurchase(),
        ];
        return array_filter($criteria, static function ($v): bool {
            return null !== $v;
        });
    }
}
