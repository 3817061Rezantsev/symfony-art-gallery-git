<?php

namespace Sergo\ArtGallery\Service\SearchPicturesService;

use DateTimeImmutable;

final class SearchPicturesCriteria
{
    /**
     * Id картины
     *
     * @var int|null
     */
    private ?int $id = null;

    /**
     * Имя картины
     *
     * @var string|null
     */
    private ?string $name = null;

    /**
     * Автор картины
     *
     * @var int|null
     */
    private ?int $painterId =  null;
    /**
     * Автор картины
     *
     * @var string|null
     */
    private ?string $painterFullName = null;

    /**
     * Автор картины
     *
     * @var string|null
     */
    private ?string $painterDateOfBirth = null;

    /**
     * Автор картины
     *
     * @var string|null
     */
    private ?string $painterTelephoneNumber = null;

    /**
     * Год написания картины
     *
     * @var string|null
     */
    private ?string $year = null;

    /**
     * Год начала поика картин
     *
     * @var string|null
     */
    private ?string $start = null;


    /**
     * Конечный год написания картины
     *
     * @var string|null
     */
    private ?string $end = null;

    /**
     * @return DateTimeImmutable|null
     */
    public function getStart(): ?DateTimeImmutable
    {
        return $this->start === null ? null :
            DateTimeImmutable::createFromFormat('Y/m/d', $this->start . '/01/01');
    }

    /**
     * @param string|null $start
     * @return SearchPicturesCriteria
     */
    public function setStart(?string $start): SearchPicturesCriteria
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getEnd(): ?DateTimeImmutable
    {
        return $this->end === null ? null :
            DateTimeImmutable::createFromFormat('Y/m/d', $this->end . '/01/01');
    }

    /**
     * @param string|null $end
     * @return SearchPicturesCriteria
     */
    public function setEnd(?string $end): SearchPicturesCriteria
    {
        $this->end = $end;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return SearchPicturesCriteria
     */
    public function setId(?int $id): SearchPicturesCriteria
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return SearchPicturesCriteria
     */
    public function setName(?string $name): SearchPicturesCriteria
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPainterFullName(): ?string
    {
        return $this->painterFullName;
    }

    /**
     * @param string|null $painterFullName
     * @return SearchPicturesCriteria
     */
    public function setPainterFullName(?string $painterFullName): SearchPicturesCriteria
    {
        $this->painterFullName = $painterFullName;
        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getYear(): ?DateTimeImmutable
    {
        return $this->year === null ? null :
            DateTimeImmutable::createFromFormat('Y/m/d', $this->year . '/01/01');
    }

    /**
     * @param string|null $year
     * @return SearchPicturesCriteria
     */
    public function setYear(?string $year): SearchPicturesCriteria
    {
        $this->year = $year;
        return $this;
    }

    public function toArray(): array
    {
        $criteria = [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'painter_id' => $this->getPainterId(),
            'painter_fullName' => $this->getPainterFullName(),
            'painter_dateOfBirth' => $this->getPainterDateOfBirth(),
            'painter_telephoneNumber' => $this->getPainterTelephoneNumber(),
            'year' => $this->getYear(),
            'start' => $this->getStart(),
            'end' => $this->getEnd(),

        ];
        return array_filter($criteria, static function ($v): bool {
            return null !== $v;
        });
    }

    /**
     * @return int|null
     */
    public function getPainterId(): ?int
    {
        return $this->painterId;
    }

    /**
     * @param int|null $painterId
     * @return SearchPicturesCriteria
     */
    public function setPainterId(?int $painterId): SearchPicturesCriteria
    {
        $this->painterId = $painterId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPainterDateOfBirth(): ?string
    {
        return $this->painterDateOfBirth;
    }

    /**
     * @param string|null $painterDateOfBirth
     * @return SearchPicturesCriteria
     */
    public function setPainterDateOfBirth(?string $painterDateOfBirth): SearchPicturesCriteria
    {
        $this->painterDateOfBirth = $painterDateOfBirth;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPainterTelephoneNumber(): ?string
    {
        return $this->painterTelephoneNumber;
    }

    /**
     * @param string|null $painterTelephoneNumber
     * @return SearchPicturesCriteria
     */
    public function setPainterTelephoneNumber(?string $painterTelephoneNumber): SearchPicturesCriteria
    {
        $this->painterTelephoneNumber = $painterTelephoneNumber;
        return $this;
    }
}
