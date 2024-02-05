<?php

namespace Sergo\ArtGallery\Entity;

/**
 * интерфейс для работы с сущностью TicketPurchaseReport
 */
interface TicketPurchaseReportRepositoryInterface
{
    /**
     * Поиск сущности по заданным критериям
     * @param array $criteria
     * @return TicketPurchaseReport[]
     */
    public function findBy(array $criteria): array;

    /**
     * Сохранить данные о сущности
     * @param TicketPurchaseReport $entity
     * @return TicketPurchaseReport
     */
    public function add(TicketPurchaseReport $entity): TicketPurchaseReport;

    /**
     * Получить новый идентификатор
     * @return int
     */
    public function nextId(): int;
}
