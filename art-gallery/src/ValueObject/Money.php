<?php

namespace Sergo\ArtGallery\ValueObject;

/**
 * деньги
 */
final class Money
{
    /**
     * кол-во
     * @var int
     */
    private int $amount;

    /**
     * деньги в формате с плав точкой
     * @var float|null
     */
    private ?float $decimal = null;

    /**
     * валюта
     * @var Currency
     */
    private Currency $currency;

    /**
     * @param int      $amount   - кол-во
     * @param Currency $currency - валюта
     */
    public function __construct(int $amount, Currency $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return float
     */
    public function getDecimal(): float
    {
        if (null === $this->decimal) {
            $this->decimal = $this->amount / 100;
        }
        return $this->decimal;
    }

    /**
     * @param float $decimal
     */
    public function setDecimal(float $decimal): void
    {
        $this->decimal = $decimal;
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @param Currency $currency
     */
    public function setCurrency(Currency $currency): void
    {
        $this->currency = $currency;
    }
}
