<?php

namespace Sergo\ArtGallery\ValueObject;

use DateTimeImmutable;
use DateTimeInterface;
use Sergo\ArtGallery\Exception;

/**
 * закуп цена
 */
final class PurchasePrice
{
    /**
     * время, когда была получена инфа о цакуп цене
     * @var DateTimeInterface
     */
    private DateTimeInterface $date;

    /**
     * Цена
     * @var Money
     */
    private Money $money;

    /**
     * @param DateTimeInterface $date  - время, когда была получена инфа о цакуп цене
     * @param Money             $money - Цена
     */
    public function __construct(DateTimeInterface $date, Money $money)
    {
        $this->date = $date;
        $this->money = $money;
    }

    /**
     * @return DateTimeInterface
     */
    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @return Money
     */
    public function getMoney(): Money
    {
        return $this->money;
    }
}
