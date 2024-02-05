<?php

namespace Sergo\ArtGallery\Entity;

/**
 * интерфейс для работы с сущностью Painter
 */
interface PainterRepositoryInterface
{
    /**
     * Поиск сущности по заданным критериям
     * @param array $criteria
     * @return Painter[]
     */
    public function findBy(array $criteria): array;
}
