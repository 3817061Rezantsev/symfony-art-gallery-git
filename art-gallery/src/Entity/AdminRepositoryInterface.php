<?php

namespace Sergo\ArtGallery\Entity;

/**
 * интерфейс для работы с сущностью Admin
 */
interface AdminRepositoryInterface
{
    /**
     * Поиск сущности по заданным критериям
     * @return Admin[]
     */
    public function findBy(array $criteria): array;


    /**
     * Поиск по логину
     * @param string $login
     * @return Admin|null
     */
    public function findAdminByLogin(string $login): ?Admin;
}
