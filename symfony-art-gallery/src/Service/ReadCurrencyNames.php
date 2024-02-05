<?php

namespace Sergo\ArtGallery\Service;

use Doctrine\ORM\EntityManagerInterface;
use Sergo\ArtGallery\ValueObject\Currency;

class ReadCurrencyNames
{
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function returnCurrencyNames(): array
    {
        $currencies = $this->em->getRepository(Currency::class)->findAll();
        $names = [];
        foreach ($currencies as $currency) {
            $names[] = $currency->getName();
        }
        return $names;
    }
}