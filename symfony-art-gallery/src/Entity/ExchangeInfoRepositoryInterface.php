<?php

namespace Sergo\ArtGallery\Entity;

interface ExchangeInfoRepositoryInterface
{
    /**
     * Поиск сущности по заданным критериям
     * @param array $criteria
     * @return ExchangeInfo[]
     */
    public function findBy(array $criteria): array;

    /**
     * Получить новый идентификатор
     * @return int
     */
    public function nextId(): int;

    /**
     * Сохранить данные о сущности
     * @param ExchangeInfo $entity
     * @return ExchangeInfo
     */
    public function add(ExchangeInfo $entity): ExchangeInfo;
}