<?php

namespace Sergo\ArtGallery\Service;

use Sergo\ArtGallery\Entity\Picture;
use Sergo\ArtGallery\Entity\PictureRepositoryInterface;
use Sergo\ArtGallery\Entity\TagFactoryInterface;
use Sergo\ArtGallery\Service\UpdatePictureTagsService\NewTagsDto;
use Sergo\ArtGallery\Service\UpdatePictureTagsService\UpdatedPictureDto;

class UpdatePictureTagsService
{
    private PictureRepositoryInterface $pictureRepository;
    private TagFactoryInterface $tagFactory;

    /**
     * @param PictureRepositoryInterface $pictureRepository
     * @param TagFactoryInterface        $tagFactory
     */
    public function __construct(
        PictureRepositoryInterface $pictureRepository,
        TagFactoryInterface $tagFactory
    ) {
        $this->pictureRepository = $pictureRepository;
        $this->tagFactory = $tagFactory;
    }

    public function addNewTags(NewTagsDto $tagsDto): UpdatedPictureDto
    {
        /** @var Picture $picture */
        $picture = $this->pictureRepository->findOneBy(['id' => $tagsDto->getPictureId()]);
        $tagsObj = [];
        foreach ($tagsDto->getTags() as $tagDto) {
            $tagsObj[] = $this->tagFactory->create($tagDto);
        }
        $picture->addTags(...$tagsObj);
        $tags = [];
        foreach ($picture->getTags() as $tag) {
            $tags[] = $tag->getName();
        }
        $picture->setRestavrationDate(new \DateTimeImmutable());
        return new UpdatedPictureDto(
            $picture->getId(),
            $picture->getName(),
            $tags
        );
    }
}