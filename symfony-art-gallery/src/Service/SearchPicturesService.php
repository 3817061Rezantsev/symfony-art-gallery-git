<?php

namespace Sergo\ArtGallery\Service;

use Sergo\ArtGallery\Entity\Picture;
use Sergo\ArtGallery\Entity\PictureRepositoryInterface;
use Psr\Log\LoggerInterface;
use Sergo\ArtGallery\Service\SearchPicturesService\PainterDto;

/**
 * сервис поиска картины
 */
class SearchPicturesService
{
    /**
     * Логгер
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var PictureRepositoryInterface - интерфейс для работы с сущностью Picture
     */
    private PictureRepositoryInterface $pictureRepository;

    /**
     * @param LoggerInterface            $logger            - Логгер
     * @param PictureRepositoryInterface $pictureRepository - интерфейс для работы с сущностью Picture
     */
    public function __construct(
        LoggerInterface $logger,
        PictureRepositoryInterface $pictureRepository
    ) {
        $this->logger = $logger;
        $this->pictureRepository = $pictureRepository;
    }

    /**
     * Поиск по критериям
     *
     * @param SearchPicturesService\SearchPicturesCriteria $searchCriteria
     * @return SearchPicturesService\PictureDto[]
     */
    public function search(SearchPicturesService\SearchPicturesCriteria $searchCriteria): array
    {
        $entitiesCollection = $this->pictureRepository->findBy($searchCriteria->toArray());
        $dtoCollection = [];
        foreach ($entitiesCollection as $entity) {
            $dtoCollection[] = $this->createDto($entity);
        }
        $this->logger->debug('found Pictures: ' . count($entitiesCollection));
        return $dtoCollection;
    }

    /**
     * Создание DTO
     *
     * @param Picture $picture
     *
     * @return SearchPicturesService\PictureDto
     */
    private function createDto(Picture $picture): SearchPicturesService\PictureDto
    {
        $painter = $picture->getPainter();
        $date = null === $painter->getDateOfBirth() ? null : $painter->getDateOfBirth()->format('Y.m.d');
        $painterDto = new PainterDto(
            $painter->getId(),
            $painter->getFullName(),
            $date,
            $painter->getTelephoneNumber(),
        );
        return new SearchPicturesService\PictureDto(
            $picture->getId(),
            $picture->getName(),
            $painterDto,
            $picture->getYear()->format('Y')
        );
    }
}
