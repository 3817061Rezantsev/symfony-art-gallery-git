<?php

namespace Sergo\ArtGallery\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sergo\ArtGallery\Exception\DomainException;

/**
 * Художник
 * @ORM\Entity(repositoryClass=\Sergo\ArtGallery\Repository\PainterDoctrineRepository::class)
 * @ORM\Table(
 *     name="painters",
 *     indexes={
 *       @ORM\Index(name="painters_full_name_idx", columns={"full_name"}),
 *       @ORM\Index(name="painters_telephone_number_idx", columns={"telephone_number"})
 *     }
 * )
 */
class Painter
{
    /**
     * ID художника
     * @ORM\Id()
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @var int
     */
    protected int $id;

    /**
     * Имя художника
     * @ORM\Column(name="full_name", type="string", length=255, nullable=false)
     * @var string
     */
    protected string $fullName;

    /**
     * Дата рождения художника
     * @ORM\Column(name="date_of_birth", type="date_immutable", nullable=true)
     * @var ?DateTimeImmutable
     */
    protected ?DateTimeImmutable $dateOfBirth;

    /**
     * Номер телефона художника
     * @ORM\Column(name="telephone_number", type="string", length=30, nullable=true)
     * @var ?string
     */
    protected ?string $telephoneNumber;

    /**
     * @ORM\OneToMany(
     *     targetEntity=\Sergo\ArtGallery\Entity\Picture::class,
     *     mappedBy="painter"
     * )
     * @var Collection|Picture[]
     */
    private Collection $pictures;

    /**
     * /**
     * @ORM\OneToMany(
     *     targetEntity=\Sergo\ArtGallery\Entity\Srcatch::class,
     *     mappedBy="painter"
     * )
     * @var Collection
     */
    private Collection $srcatches;

    /*-----------------------------------------------------------------*/
    /**
     * @param int                $id              - ID художника
     * @param string             $fullName        - Имя художника
     * @param ?DateTimeImmutable $dateOfBirth     - Дата рождения художника
     * @param ?string            $telephoneNumber - Номер телефона художника
     * @param array         $srcatches
     * @param array              $pictures
     */
    public function __construct(
        int $id,
        string $fullName,
        ?DateTimeImmutable $dateOfBirth,
        ?string $telephoneNumber,
        array $srcatches = [],
        array $pictures = []
    ) {
        $this->id = $id;
        $this->fullName = $fullName;
        $this->dateOfBirth = $dateOfBirth;
        $this->telephoneNumber = $telephoneNumber;
        foreach ($pictures as $picture) {
            if (!$picture instanceof Picture) {
                throw new DomainException('Некорректный формат данных о художниках');
            }
        }
        $this->pictures = new ArrayCollection($pictures);
        $this->srcatches = new ArrayCollection($srcatches);
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
     * @return DateTimeImmutable|null
     */
    public function getDateOfBirth(): ?DateTimeImmutable
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

    /**
     * @return Picture[]
     */
    public function getPictures(): array
    {
        return $this->pictures->toArray();
    }

    /**
     * @return array
     */
    public function getSrcatches()
    {
        return $this->srcatches->toArray();
    }
}
