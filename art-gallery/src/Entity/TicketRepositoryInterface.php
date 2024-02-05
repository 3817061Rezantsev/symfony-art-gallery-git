<?php

namespace Sergo\ArtGallery\Entity;

/**
 * интерфейс для работы с сущностью Ticket
 */
interface TicketRepositoryInterface
{
    /**
     * Поиск сущности по заданным критериям
     * @param array $criteria
     * @return Ticket[]
     */
    public function findBy(array $criteria): array;
}
