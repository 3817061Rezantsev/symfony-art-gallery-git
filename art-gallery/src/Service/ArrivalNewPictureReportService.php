<?php

namespace Sergo\ArtGallery\Service;

use DateTimeImmutable;
use Sergo\ArtGallery\Entity\CurrencyFactoryInterface;
use Sergo\ArtGallery\Entity\PicturePurchaseReport;
use Sergo\ArtGallery\Entity\PicturePurchaseReportRepositoryInterface;
use Sergo\ArtGallery\Entity\PictureRepositoryInterface;
use Sergo\ArtGallery\Entity\VisitorRepositoryInterface;
use Sergo\ArtGallery\Service\ArrivalNewPictureReportService\NewPictureReportDto;
use Sergo\ArtGallery\Service\ArrivalNewPictureReportService\ResultRegisteringPictureReportDto;
use Sergo\ArtGallery\ValueObject\Money;
use Sergo\ArtGallery\ValueObject\PurchasePrice;

/**
 * сервис для добавления новых актов о покупке картин
 */
class ArrivalNewPictureReportService
{
    /**
     * @var PictureRepositoryInterface - интерфейс для работы с сущностью Picture
     */
    private PictureRepositoryInterface $pictureRepository;
    /**
     * @var VisitorRepositoryInterface - интерфейс для работы с сущностью Visitor
     */
    private VisitorRepositoryInterface $visitorRepository;
    /**
     * @var PicturePurchaseReportRepositoryInterface - интерфейс для работы с сущностью PicturePurchaseReport
     */
    private PicturePurchaseReportRepositoryInterface $pictureReportRepository;
    /**
     * @var CurrencyFactoryInterface - фабрика для реализации поиска объекта Currency
     */
    private CurrencyFactoryInterface $currencyDbFactory;

    /**
     * @param PictureRepositoryInterface $pictureRepository
     * - интерфейс для работы с сущностью Picture
     * @param VisitorRepositoryInterface $visitorRepository
     * - интерфейс для работы с сущностью Visitor
     * @param PicturePurchaseReportRepositoryInterface $pictureReportRepository
     * - интерфейс для работы с сущностью PicturePurchaseReport
     * @param CurrencyFactoryInterface $currencyDbFactory
     * - фабрика для реализации поиска объекта Currency
     */
    public function __construct(
        PictureRepositoryInterface $pictureRepository,
        VisitorRepositoryInterface $visitorRepository,
        PicturePurchaseReportRepositoryInterface $pictureReportRepository,
        CurrencyFactoryInterface $currencyDbFactory
    ) {
        $this->pictureRepository = $pictureRepository;
        $this->visitorRepository = $visitorRepository;
        $this->pictureReportRepository = $pictureReportRepository;
        $this->currencyDbFactory = $currencyDbFactory;
    }

    /**
     * добавление сущности
     * @param NewPictureReportDto $pictureReportDto
     * @return ResultRegisteringPictureReportDto
     */
    public function registerPictureReport(NewPictureReportDto $pictureReportDto): ResultRegisteringPictureReportDto
    {
        $visitorArr = $this->visitorRepository->findBy(['id' => $pictureReportDto->getVisitorId()]);
        $visitor = $visitorArr[0];
        $pictures = $this->pictureRepository->findBy(['id' => $pictureReportDto->getPictureId()]);
        $picture = $pictures[0];
        $date = DateTimeImmutable::createFromFormat('Y-m-d H:i', $pictureReportDto->getDateOfPurchase());
        $purchasePrice = new PurchasePrice(
            $date,
            new Money(
                $pictureReportDto->getCost(),
                $this->currencyDbFactory->findByName($pictureReportDto->getCurrency())
            )
        );
        $entity = new PicturePurchaseReport(
            $this->pictureReportRepository->nextId(),
            $visitor,
            $picture,
            $purchasePrice
        );
        $this->pictureReportRepository->add($entity);
        return new ResultRegisteringPictureReportDto($entity->getId());
    }
}
