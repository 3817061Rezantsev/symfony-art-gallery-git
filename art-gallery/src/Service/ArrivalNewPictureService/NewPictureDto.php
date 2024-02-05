<?php

namespace Sergo\ArtGallery\Service\ArrivalNewPictureService;

class NewPictureDto
{
    /**
     * Имя картины
     *
     * @var string
     */
    private string $name;

    /**
     * Автор картины
     *
     * @var int
     */
    private int $painterId;

    /**
     * Год написания картины
     *
     * @var string
     */
    private string $year;

    /**
     * @param string $name
     * @param int $painterId
     * @param string $year
     */
    public function __construct(string $name, int $painterId, string $year)
    {
        $this->name = $name;
        $this->painterId = $painterId;
        $this->year = $year;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPainterId(): int
    {
        return $this->painterId;
    }

    /**
     * @return string
     */
    public function getYear(): string
    {
        return $this->year;
    }
}
