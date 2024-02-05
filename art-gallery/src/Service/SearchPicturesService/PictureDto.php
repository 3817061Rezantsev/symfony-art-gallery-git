<?php

namespace Sergo\ArtGallery\Service\SearchPicturesService;

final class PictureDto
{
    /**
     * Id картины
     *
     * @var int
     */
    private int $id;

    /**
     * Имя картины
     *
     * @var string
     */
    private string $name;

    /**
     * Автор картины
     *
     * @var PainterDto
     */
    private PainterDto $painterDto;

    /**
     * Год написания картины
     *
     * @var string
     */
    private string $year;

    /**
     * @param int $id
     * @param string $name
     * @param PainterDto $painterDto
     * @param string $year
     */
    public function __construct(int $id, string $name, PainterDto $painterDto, string $year)
    {
        $this->id = $id;
        $this->name = $name;
        $this->painterDto = $painterDto;
        $this->year = $year;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return PainterDto
     */
    public function getPainterDto(): PainterDto
    {
        return $this->painterDto;
    }

    /**
     * @return string
     */
    public function getYear(): string
    {
        return $this->year;
    }
}
