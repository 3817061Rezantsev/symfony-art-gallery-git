<?php

namespace Sergo\ArtGallery\Service\SearchPictureReportsService;

class PictureReportDto
{
    /**
     * Номер отчета
     *
     * @var int
     */
    private int $id;

    /**
     * Номер посетителя
     *
     * @var VisitorDto
     */
    private VisitorDto $visitor;

    /**
     * Номер картины
     *
     * @var PictureDto
     */
    private PictureDto $picture;

    private string $currency;
    private string $dateOfPurchase;
    private int $cost;


    /*----------------------------------------------------------------------*/
    /**
     * @param int $id
     * @param VisitorDto $visitor
     * @param PictureDto $picture
     * @param string $currency
     * @param string $dateOfPurchase
     * @param int $cost
     */
    public function __construct(
        int $id,
        VisitorDto $visitor,
        PictureDto $picture,
        string $currency,
        string $dateOfPurchase,
        int $cost
    ) {
        $this->id = $id;
        $this->visitor = $visitor;
        $this->picture = $picture;
        $this->currency = $currency;
        $this->dateOfPurchase = $dateOfPurchase;
        $this->cost = $cost;
    }

    /**
     * Получить ID отчета
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Получить номер посетителя
     *
     * @return VisitorDto
     */
    public function getVisitor(): VisitorDto
    {
        return $this->visitor;
    }

    /**
     * Получить номер картины
     *
     * @return PictureDto
     */
    public function getPicture(): PictureDto
    {
        return $this->picture;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
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
}
