<?php

namespace Sergo\ArtGallery\Service;

use DateTimeImmutable;
use Sergo\ArtGallery\Entity\PainterRepositoryInterface;
use Sergo\ArtGallery\Entity\Picture;
use Sergo\ArtGallery\Entity\PictureRepositoryInterface;
use Sergo\ArtGallery\Service\ArrivalNewPictureService\NewPictureDto;
use Sergo\ArtGallery\Service\ArrivalNewPictureService\ResultRegisteringPictureDto;

/**
 * сервис для добавления новых картин
 */
class ArrivalNewPictureService
{
    /**
     * @var PictureRepositoryInterface - интерфейс для работы с сущностью Picture
     */
    private PictureRepositoryInterface $pictureRepository;
    /**
     * @var PainterRepositoryInterface - интерфейс для работы с сущностью Painter
     */
    private PainterRepositoryInterface $painterRepository;

    /**
     * @param PictureRepositoryInterface $pictureRepository - интерфейс для работы с сущностью Picture
     * @param PainterRepositoryInterface $painterRepository - интерфейс для работы с сущностью Painter
     */
    public function __construct(
        PictureRepositoryInterface $pictureRepository,
        PainterRepositoryInterface $painterRepository
    ) {
        $this->pictureRepository = $pictureRepository;
        $this->painterRepository = $painterRepository;
    }

    /**
     * регистрация новой картины
     * @param NewPictureDto $newPictureDto
     * @return ResultRegisteringPictureDto
     */
    public function registerPicture(NewPictureDto $newPictureDto): ResultRegisteringPictureDto
    {
        $painterArr = $this->painterRepository->findBy(['id' => $newPictureDto->getPainterId()]);
        $painter = $painterArr[0];
        $date = DateTimeImmutable::createFromFormat('Y-m-d', "{$newPictureDto->getYear()}-01-01");
        $entity = new Picture(
            $this->pictureRepository->nextId(),
            $newPictureDto->getName(),
            $painter,
            $date
        );
        $this->pictureRepository->add($entity);
        return new ResultRegisteringPictureDto($entity->getId());
    }
}
