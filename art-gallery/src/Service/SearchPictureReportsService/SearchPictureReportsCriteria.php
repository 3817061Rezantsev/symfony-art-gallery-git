<?php

namespace Sergo\ArtGallery\Service\SearchPictureReportsService;

use DateTimeImmutable;

class SearchPictureReportsCriteria
{
    /**
     * Номер отчета
     *
     * @var int|null
     */
    private ?int $id = null;

    /**
     * Id картины
     *
     * @var int|null
     */
    private ?int $pictureId = null;

    /**
     * Имя картины
     *
     * @var string|null
     */
    private ?string $pictureName = null;

    private ?int $picturePainterId = null;
    /**
     * Автор картины
     *
     * @var string|null
     */
    private ?string $picturePainterFullName = null;
    private ?string $picturePainterDateOfBirth = null;
    private ?string $picturePainterTelephoneNumber = null;

    /**
     * Год написания картины
     *
     * @var string|null
     */
    private ?string $pictureYear = null;

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

    private ?string $dateOfPurchase = null;

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
     * @return SearchPictureReportsCriteria
     */
    public function setId(?int $id): SearchPictureReportsCriteria
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPictureId(): ?int
    {
        return $this->pictureId;
    }

    /**
     * @param int|null $pictureId
     * @return SearchPictureReportsCriteria
     */
    public function setPictureId(?int $pictureId): SearchPictureReportsCriteria
    {
        $this->pictureId = $pictureId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPictureName(): ?string
    {
        return $this->pictureName;
    }

    /**
     * @param string|null $pictureName
     * @return SearchPictureReportsCriteria
     */
    public function setPictureName(?string $pictureName): SearchPictureReportsCriteria
    {
        $this->pictureName = $pictureName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPicturePainterFullName(): ?string
    {
        return $this->picturePainterFullName;
    }

    /**
     * @param string|null $picturePainterFullName
     * @return SearchPictureReportsCriteria
     */
    public function setPicturePainterFullName(?string $picturePainterFullName): SearchPictureReportsCriteria
    {
        $this->picturePainterFullName = $picturePainterFullName;
        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getPictureYear(): ?DateTimeImmutable
    {
        return $this->pictureYear === null ? null :
            DateTimeImmutable::createFromFormat('Y/m/d', $this->pictureYear . '/01/01');
    }

    /**
     * @param string|null $pictureYear
     * @return SearchPictureReportsCriteria
     */
    public function setPictureYear(?string $pictureYear): SearchPictureReportsCriteria
    {
        $this->pictureYear = $pictureYear;
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
     * @return SearchPictureReportsCriteria
     */
    public function setVisitorId(?int $visitorId): SearchPictureReportsCriteria
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
     * @return SearchPictureReportsCriteria
     */
    public function setVisitorFullName(?string $visitorFullName): SearchPictureReportsCriteria
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
     * @return SearchPictureReportsCriteria
     */
    public function setVisitorDateOfBirth(?string $visitorDateOfBirth): SearchPictureReportsCriteria
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
     * @return SearchPictureReportsCriteria
     */
    public function setVisitorTelephoneNumber(?string $visitorTelephoneNumber): SearchPictureReportsCriteria
    {
        $this->visitorTelephoneNumber = $visitorTelephoneNumber;
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
     * @return SearchPictureReportsCriteria
     */
    public function setDateOfPurchase(?string $dateOfPurchase): SearchPictureReportsCriteria
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
     * @return SearchPictureReportsCriteria
     */
    public function setCost(?int $cost): SearchPictureReportsCriteria
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
     * @return SearchPictureReportsCriteria
     */
    public function setCurrency(?string $currency): SearchPictureReportsCriteria
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getPicturePainterId(): ?int
    {
        return $this->picturePainterId;
    }

    /**
     * @param int|null $picturePainterId
     * @return SearchPictureReportsCriteria
     */
    public function setPicturePainterId(?int $picturePainterId): SearchPictureReportsCriteria
    {
        $this->picturePainterId = $picturePainterId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPicturePainterDateOfBirth(): ?string
    {
        return $this->picturePainterDateOfBirth;
    }

    /**
     * @param string|null $picturePainterDateOfBirth
     * @return SearchPictureReportsCriteria
     */
    public function setPicturePainterDateOfBirth(?string $picturePainterDateOfBirth): SearchPictureReportsCriteria
    {
        $this->picturePainterDateOfBirth = $picturePainterDateOfBirth;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPicturePainterTelephoneNumber(): ?string
    {
        return $this->picturePainterTelephoneNumber;
    }

    /**
     * @param string|null $picturePainterTelephoneNumber
     * @return SearchPictureReportsCriteria
     */
    public function setPicturePainterTelephoneNumber(
        ?string $picturePainterTelephoneNumber
    ): SearchPictureReportsCriteria {
        $this->picturePainterTelephoneNumber = $picturePainterTelephoneNumber;
        return $this;
    }


    public function toArray(): array
    {
        $criteria = [
            'id' => $this->getId(),
            'picture_id' => $this->getPictureId(),
            'visitor_id' => $this->getVisitorId(),
            'visitor_fullName' => $this->getVisitorFullName(),
            'visitor_dateOfBirth' => $this->getVisitorDateOfBirth(),
            'visitor_telephoneNumber' => $this->getVisitorTelephoneNumber(),
            'picture_name' => $this->getPictureName(),
            'picture_painter_id' => $this->getPicturePainterId(),
            'picture_painter_fullName' => $this->getPicturePainterFullName(),
            'picture_painter_dateOfBirth' => $this->getPicturePainterDateOfBirth(),
            'picture_painter_telephoneNumber' => $this->getPicturePainterTelephoneNumber(),
            'picture_year' => $this->getPictureYear(),
            'cost' => $this->getCost(),
            'currency' => $this->getCurrency(),
            'dateOfPurchase' => $this->getDateOfPurchase(),
        ];
        return array_filter($criteria, static function ($v): bool {
            return null !== $v;
        });
    }
}
