<?php

namespace Sergo\ArtGallery\Entity;

/**
 * интерфейс для работы с сущностью PicturePurchaseReport
 */
interface PicturePurchaseReportRepositoryInterface
{
    /**
     * Поиск сущности по заданным критериям
     * @param array $criteria
     * @return PicturePurchaseReport[]
     */
    public function findBy(array $criteria): array;

    /**
     * Получить новый идентификатор
     * @return int
     */
    public function nextId(): int;

    /**
     * Сохранить данные о сущности
     * @param PicturePurchaseReport $entity
     * @return PicturePurchaseReport
     */
    public function add(PicturePurchaseReport $entity): PicturePurchaseReport;
}
