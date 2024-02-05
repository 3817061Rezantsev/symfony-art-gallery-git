<?php

namespace Sergo\ArtGallery\Service\UpdatePictureTagsService;

class NewTagsDto
{
    private int $pictureId;
    private array $tags;

    /**
     * @param int   $pictureId
     * @param array $tags
     */
    public function __construct(int $pictureId, array $tags)
    {
        $this->pictureId = $pictureId;
        $this->tags = $tags;
    }

    /**
     * @return int
     */
    public function getPictureId(): int
    {
        return $this->pictureId;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

}