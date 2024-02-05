<?php

namespace Sergo\ArtGallery\Entity;

use Sergo\ArtGallery\ValueObject\Tag;

/**
 * итрерфейс для фабрики создания объекта Tag
 */
interface TagFactoryInterface
{
    /**
     * поиск Tag по имени и созданиее
     * @param string $name
     * @return Tag
     */
    public function create(string $name): Tag;
}
