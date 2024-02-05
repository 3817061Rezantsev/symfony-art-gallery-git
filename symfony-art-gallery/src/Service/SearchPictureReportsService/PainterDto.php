<?php

namespace Sergo\ArtGallery\Service\SearchPictureReportsService;

class PainterDto
{
    /**
     * ID пользователя
     *
     * @var int
     */
    protected int $id;

    /**
     * Имя пользователя
     *
     * @var string
     */
    protected string $fullName;

    /**
     * Дата рождения пользователя
     *
     * @var ?string
     */
    protected ?string $dateOfBirth;

    /**
     * Номер телефона пользователя
     *
     * @var ?string
     */
    protected ?string $telephoneNumber;

    /*-----------------------------------------------------------------*/
    /**
     * @param int $id
     * @param string $fullName
     * @param ?string $dateOfBirth
     * @param ?string $telephoneNumber
     */
    public function __construct(int $id, string $fullName, ?string $dateOfBirth, ?string $telephoneNumber)
    {
        $this->id = $id;
        $this->fullName = $fullName;
        $this->dateOfBirth = $dateOfBirth;
        $this->telephoneNumber = $telephoneNumber;
    }

    /**
     * Получить ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Получить Имя
     *
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * Получить дату рождения
     *
     * @return string|null
     */
    public function getDateOfBirth(): ?string
    {
        return $this->dateOfBirth;
    }

    /**
     * Получить номер телефона
     *
     * @return string|null
     */
    public function getTelephoneNumber(): ?string
    {
        return $this->telephoneNumber;
    }
}
