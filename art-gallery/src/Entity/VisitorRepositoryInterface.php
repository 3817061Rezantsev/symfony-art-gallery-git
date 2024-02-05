<?php

namespace Sergo\ArtGallery\Entity;

/**
 * интерфейс для работы с сущностью Visitor
 */
interface VisitorRepositoryInterface
{
    /**
     * Поиск сущности по заданным критериям
     * @param array $criteria
     * @return Visitor[]
     */
    public function findBy(array $criteria): array;
}
