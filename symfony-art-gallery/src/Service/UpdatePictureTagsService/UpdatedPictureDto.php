<?php

namespace Sergo\ArtGallery\Service\UpdatePictureTagsService;

class UpdatedPictureDto
{
    private int $id;
    private string $name;
    private array $tags;

    /**
     * @param int    $id
     * @param string $name
     * @param array  $tags
     */
    public function __construct(int $id, string $name, array $tags)
    {
        $this->id = $id;
        $this->name = $name;
        $this->tags = $tags;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}
