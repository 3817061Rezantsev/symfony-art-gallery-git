<?php

namespace Sergo\ArtGallery\Service;

use DateTimeImmutable;
use Sergo\ArtGallery\Entity\CurrencyFactoryInterface;
use Sergo\ArtGallery\Entity\ExchangeInfo;
use Sergo\ArtGallery\Entity\ExchangeInfoRepositoryInterface;
use Sergo\ArtGallery\Entity\PicturePurchaseReport;
use Sergo\ArtGallery\Entity\PicturePurchaseReportRepositoryInterface;
use Sergo\ArtGallery\Entity\PictureRepositoryInterface;
use Sergo\ArtGallery\Entity\VisitorRepositoryInterface;
use Sergo\ArtGallery\Service\ExchangePictureService\ExchangeDataDto;
use Sergo\ArtGallery\Service\ExchangePictureService\ExchangedRegistrationDto;
use Sergo\ArtGallery\ValueObject\Money;
use Sergo\ArtGallery\ValueObject\PurchasePrice;

class ExchangePictureService
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

    private ExchangeInfoRepositoryInterface $exchangeInfoRepository;

    /**
     * @param PictureRepositoryInterface               $pictureRepository
     * - интерфейс для работы с сущностью Picture
     * @param VisitorRepositoryInterface               $visitorRepository
     * - интерфейс для работы с сущностью Visitor
     * @param PicturePurchaseReportRepositoryInterface $pictureReportRepository
     * - интерфейс для работы с сущностью PicturePurchaseReport
     * @param CurrencyFactoryInterface                 $currencyDbFactory
     * @param ExchangeInfoRepositoryInterface          $exchangeInfoRepository
     * - фабрика для реализации поиска объекта Currency
     */
    public function __construct(
        PictureRepositoryInterface $pictureRepository,
        VisitorRepositoryInterface $visitorRepository,
        PicturePurchaseReportRepositoryInterface $pictureReportRepository,
        CurrencyFactoryInterface $currencyDbFactory,
        ExchangeInfoRepositoryInterface $exchangeInfoRepository
    ) {
        $this->pictureRepository = $pictureRepository;
        $this->visitorRepository = $visitorRepository;
        $this->pictureReportRepository = $pictureReportRepository;
        $this->currencyDbFactory = $currencyDbFactory;
        $this->exchangeInfoRepository = $exchangeInfoRepository;
    }

    /**
     * добавление сущности
     * @param ExchangeDataDto $pictureReportDto
     * @return ExchangedRegistrationDto
     */
    public function registerExchange(ExchangeDataDto $pictureReportDto): ExchangedRegistrationDto
    {
        $visitorArr = $this->visitorRepository->findBy(['id' => $pictureReportDto->getFirstVisitorId()]);
        $visitor1 = $visitorArr[0];
        $pictures = $this->pictureRepository->findBy(['id' => $pictureReportDto->getFirstPictureId()]);
        $picture1 = $pictures[0];
        $visitorArr = $this->visitorRepository->findBy(['id' => $pictureReportDto->getSecondVisitorId()]);
        $visitor2 = $visitorArr[0];
        $pictures = $this->pictureRepository->findBy(['id' => $pictureReportDto->getSecondPictureId()]);
        $picture2 = $pictures[0];
        $date = DateTimeImmutable::createFromFormat('Y-m-d H:i', $pictureReportDto->getDateOfPurchase());
        $purchasePrice1 = new PurchasePrice(
            $date,
            new Money(
                0,
                $this->currencyDbFactory->findByName($pictureReportDto->getCurrency())
            )
        );
        $purchasePrice2 = new PurchasePrice(
            $date,
            new Money(
                $pictureReportDto->getCost(),
                $this->currencyDbFactory->findByName($pictureReportDto->getCurrency())
            )
        );
        $entity1 = new PicturePurchaseReport(
            $this->pictureReportRepository->nextId(),
            $visitor1,
            $picture2,
            $purchasePrice1
        );
        $entity2 = new PicturePurchaseReport(
            $this->pictureReportRepository->nextId(),
            $visitor2,
            $picture1,
            $purchasePrice2
        );
//        $entity1->setRelatedPurchasePrice($entity2);
//        $entity2->setRelatedPurchasePrice($entity1);
        $exchangeInfo = new ExchangeInfo(
            $this->exchangeInfoRepository->nextId(),
            $entity1,
            $entity2
        );
        $this->pictureReportRepository->add($entity1);
        $this->pictureReportRepository->add($entity2);
        $this->exchangeInfoRepository->add($exchangeInfo);
        return new ExchangedRegistrationDto($entity1->getId(), $entity2->getId());
    }

}