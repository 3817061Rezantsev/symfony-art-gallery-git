<?php

namespace Sergo\ArtGallery\Service\SearchVisitorsService;

class SearchVisitorCriteria
{
    /**
     * Номер
     *
     * @var int|null
     */
    private ?int $id = null;

    /**
     * Имя
     *
     * @var ?string
     */
    protected ?string $fullName = null;

    /**
     * Дата рождения
     *
     * @var ?string
     */
    protected ?string $dateOfBirth = null;

    /**
     * Номер телефона
     *
     * @var ?string
     */
    protected ?string $telephoneNumber = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return SearchVisitorCriteria
     */
    public function setId(?int $id): SearchVisitorCriteria
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     * @param string|null $fullName
     * @return SearchVisitorCriteria
     */
    public function setFullName(?string $fullName): SearchVisitorCriteria
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDateOfBirth(): ?string
    {
        return $this->dateOfBirth;
    }

    /**
     * @param string|null $dateOfBirth
     * @return SearchVisitorCriteria
     */
    public function setDateOfBirth(?string $dateOfBirth): SearchVisitorCriteria
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTelephoneNumber(): ?string
    {
        return $this->telephoneNumber;
    }

    /**
     * @param string|null $telephoneNumber
     * @return SearchVisitorCriteria
     */
    public function setTelephoneNumber(?string $telephoneNumber): SearchVisitorCriteria
    {
        $this->telephoneNumber = $telephoneNumber;
        return $this;
    }

    public function toArray(): array
    {
        $criteria = [
            'id' => $this->getId(),
            'fullName' => $this->getFullName(),
            'dateOfBirth' => $this->getDateOfBirth(),
            'telephoneNumber' => $this->getTelephoneNumber()
        ];
        return array_filter($criteria, static function ($v): bool {
            return null !== $v;
        });
    }
}
