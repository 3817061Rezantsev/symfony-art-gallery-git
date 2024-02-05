<?php

namespace Sergo\ArtGallery\Service;

use Sergo\ArtGallery\Entity\PicturePurchaseReport;
use Sergo\ArtGallery\Entity\PicturePurchaseReportRepositoryInterface;
use Psr\Log\LoggerInterface;
use Sergo\ArtGallery\Service\SearchPictureReportsService\PainterDto;
use Sergo\ArtGallery\Service\SearchPictureReportsService\PictureDto;
use Sergo\ArtGallery\Service\SearchPictureReportsService\PictureReportDto;
use Sergo\ArtGallery\Service\SearchPictureReportsService\SearchPictureReportsCriteria;
use Sergo\ArtGallery\Service\SearchPictureReportsService\VisitorDto;

/**
 * сервис поиска акта о покупке картины
 */
class SearchPictureReportsService
{
    /**
     * Логгер
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var PicturePurchaseReportRepositoryInterface - интерфейс для работы с сущностью PicturePurchaseReport
     */
    private PicturePurchaseReportRepositoryInterface $pictureReportRepository;

    /**
     * @param LoggerInterface                          $logger                  - Логгер
     * @param PicturePurchaseReportRepositoryInterface $pictureReportRepository
     * - интерфейс для работы с сущностью PicturePurchaseReport
     */
    public function __construct(
        LoggerInterface $logger,
        PicturePurchaseReportRepositoryInterface $pictureReportRepository
    ) {
        $this->logger = $logger;
        $this->pictureReportRepository = $pictureReportRepository;
    }

    /**
     * Поиск по критериям
     * @param SearchPictureReportsCriteria $searchCriteria
     * @return SearchPictureReportsService\PictureReportDto[]
     */
    public function search(SearchPictureReportsCriteria $searchCriteria): array
    {
        $entitiesCollection = $this->pictureReportRepository->findBy($searchCriteria->toArray());
        $dtoCollection = [];
        foreach ($entitiesCollection as $entity) {
            $dtoCollection[] = $this->createDto($entity);
        }
        $this->logger->debug('found text document: ' . count($entitiesCollection));
        return $dtoCollection;
    }

    /**
     * Создание DTO
     * @param PicturePurchaseReport $entity
     * @return PictureReportDto
     */
    private function createDto(PicturePurchaseReport $entity): SearchPictureReportsService\PictureReportDto
    {
        $painter = $entity->getPicture()->getPainter();
        $date = null === $painter->getDateOfBirth() ? null : $painter->getDateOfBirth()->format('Y.m.d');
        $painterDto = new PainterDto(
            $painter->getId(),
            $painter->getFullName(),
            $date,
            $painter->getTelephoneNumber(),
        );
        $pictureDto = new PictureDto(
            $entity->getPicture()->getId(),
            $entity->getPicture()->getName(),
            $painterDto,
            $entity->getPicture()->getYear()->format('Y')
        );
        $visitorDto = new VisitorDto(
            $entity->getVisitor()->getId(),
            $entity->getVisitor()->getFullName(),
            $entity->getVisitor()->getDateOfBirth()->format('Y.m.d'),
            $entity->getVisitor()->getTelephoneNumber(),
        );
        return new PictureReportDto(
            $entity->getId(),
            $visitorDto,
            $pictureDto,
            $entity->getPurchasePrice()->getMoney()->getCurrency()->getName(),
            $entity->getPurchasePrice()->getDate()->format('Y-m-d H:i'),
            $entity->getPurchasePrice()->getMoney()->getAmount()
        );
    }
}
