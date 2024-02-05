<?php

namespace Sergo\ArtGallery\ValueObject;

use Doctrine\ORM\EntityManagerInterface;
use Sergo\ArtGallery\Entity\CurrencyFactoryInterface;

class CurrencyDoctrineFactory implements CurrencyFactoryInterface
{
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @inheritDoc
     */
    public function findByName(string $name): Currency
    {
        return $this->em->getRepository(Currency::class)->findOneBy(['name' => $name]);
    }
}
