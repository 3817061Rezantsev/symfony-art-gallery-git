<?php

namespace Sergo\ArtGallery\Entity;

use Sergo\ArtGallery\ValueObject\Currency;

/**
 * итрерфейс для фабрики создания объекта Currency
 */
interface CurrencyFactoryInterface
{
    /**
     * поиск валюты по ее имени и созданиее оной
     * @param string $name
     * @return Currency
     */
    public function findByName(string $name): Currency;
}