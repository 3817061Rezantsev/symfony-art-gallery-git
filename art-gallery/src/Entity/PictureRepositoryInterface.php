<?php

namespace Sergo\ArtGallery\Entity;

/**
 * интерфейс для работы с сущностью Picture
 */
interface PictureRepositoryInterface
{
    /**
     * Поиск сущности по заданным критериям
     * @param array $criteria
     * @return Picture[]
     */
    public function findBy(array $criteria): array;

    /**
     * Сохранить данные о сущности
     * @param Picture $picture
     * @return Picture
     */
    public function add(Picture $picture): Picture;

    /**
     * Получить новый идентификатор
     * @return int
     */
    public function nextId(): int;
}
