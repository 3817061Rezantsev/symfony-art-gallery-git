<?php

namespace Sergo\ArtGallery\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sergo\ArtGallery\Exception;
use Sergo\ArtGallery\Exception\DomainException;
use Sergo\ArtGallery\ValueObject\Tag;

/**
 * Картина
 * @ORM\Entity(repositoryClass=\Sergo\ArtGallery\Repository\PictureDoctrineRepository::class)
 * @ORM\Table(
 *     name="pictures",
 *     indexes={
 *       @ORM\Index(name="pictures_name_idx", columns={"name"}),
 *       @ORM\Index(name="pictures_year_idx", columns={"year"})
 *     }
 * )
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="kind", type="string", length=60)
 * @ORM\DiscriminatorMap(
 *     {
 *     "picture" = \Sergo\ArtGallery\Entity\Picture::class,
 *     "NFTArt" = \Sergo\ArtGallery\Entity\NFTArt::class,
 *     }
 * )
 */
class Picture
{
    /**
     * Id картины
     * @ORM\Id()
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="pictures_id_seq")
     * @var int
     */
    private int $id;

    /**
     * Имя картины
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @var string
     */
    private string $name;

    /**
     * Автор картины
     * @ORM\ManyToOne(targetEntity=\Sergo\ArtGallery\Entity\Painter::class, inversedBy="pictures")
     * @ORM\JoinColumn(name="painter_id", referencedColumnName="id")
     * @var Painter
     */
    private Painter $painter;

    /**
     * Год написания картины
     * @ORM\Column(name="year", type="date_immutable", nullable=false)
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $year;

    /**
     * @ORM\Column(name="restavration_date", type="date_immutable", nullable=true)
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $restavrationDate;

    /**
     * /**
     * @ORM\OneToMany(
     *     targetEntity=\Sergo\ArtGallery\Entity\Srcatch::class,
     *     mappedBy="picture"
     * )
     * @var Collection
     */
    private Collection $srcatches;


    /**
     * @ORM\ManyToMany(targetEntity=\Sergo\ArtGallery\ValueObject\Tag::class, inversedBy="pictures")
     * @ORM\JoinTable(
     *     name="tags_to_pictures",
     *     joinColumns={
     *     @ORM\JoinColumn(name="picture_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *     @ORM\JoinColumn(name="tag_id", referencedColumnName="id", onDelete="CASCADE")
     *     }
     * )
     * @var Collection|Tag[]
     */
    private Collection $tags;
    /*---------------------------------------------------------*/
    /**
     * @param array        $srcatches
     * @param int               $id      - Id картины
     * @param string            $name    - Имя картины
     * @param Painter           $painter - Автор картины
     * @param DateTimeImmutable $year    - Год написания картины
     * @param DateTimeImmutable $restavrationDate
     */
    public function __construct(
        int $id,
        string $name,
        Painter $painter,
        DateTimeImmutable $year,
        array $tags = [],
        DateTimeImmutable $restavrationDate = null,
        array $srcatches = []
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->painter = $painter;
        $this->year = $year;
        foreach ($tags as $tag) {
            if (!$tag instanceof Tag) {
                throw new DomainException('Некорректный формат данных о художниках');
            }
        }
        $this->tags = new ArrayCollection($tags);
        $this->restavrationDate = $restavrationDate;
        $this->srcatches = new ArrayCollection($srcatches);
    }


    /**
     * Получить ID картины
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Получить имя картины
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Получить автора картины
     *
     * @return Painter
     */
    public function getPainter(): Painter
    {
        return $this->painter;
    }

    /**
     * Получить год написания картины
     *
     * @return DateTimeImmutable
     */
    public function getYear(): DateTimeImmutable
    {
        return $this->year;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags->toArray();
    }

//    public function getTagsCollection()
//    {
//        return $this->tags;
//    }

    public function addTags(Tag ...$tags): void
    {
        foreach ($tags as $tag) {
            if (!$this->tags->contains($tag)) {
                $this->tags->add($tag);
            }
        }
    }

    /**
     * @return DateTimeImmutable
     */
    public function getRestavrationDate(): DateTimeImmutable
    {
        return $this->restavrationDate;
    }

    /**
     * @param DateTimeImmutable $restavrationDate
     */
    public function setRestavrationDate(?DateTimeImmutable $restavrationDate): void
    {
        $this->restavrationDate = $restavrationDate;
    }

    /**
     * @return array
     */
    public function getSrcatches()
    {
        return $this->srcatches->toArray();
    }
}
