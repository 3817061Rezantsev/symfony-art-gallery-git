<?php

namespace Sergo\ArtGallery\Service\SearchTicketsService;

class GalleryDto
{
    /**
     * Номер галереи
     *
     * @var int
     */
    private int $id;

    /**
     * Имя галереи
     *
     * @var string
     */
    private string $name;

    /**
     * Адрес галереи
     *
     * @var string
     */
    private string $address;

    /**
     * @param int $id
     * @param string $name
     * @param string $address
     */
    public function __construct(int $id, string $name, string $address)
    {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
    }


    /**
     * Получить номер галереи
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Получить имя галереи
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * Получить адрес галереи
     *
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }
}
