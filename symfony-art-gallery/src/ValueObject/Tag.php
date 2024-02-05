<?php

namespace Sergo\ArtGallery\ValueObject;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sergo\ArtGallery\Entity\Picture;
use Sergo\ArtGallery\Exception\DomainException;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     name="tags",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="tags_name_unq", columns={"name"})
 *     }
 * )
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="tags_id_seq")
     */
    private ?int $id = null;

    /**
     * имя тега
     * @ORM\Column(name="name", type="string", length=25, nullable=false)
     * @var string
     */
    private string $name;

    /**
     * @ORM\ManyToMany(
     *     targetEntity=\Sergo\ArtGallery\Entity\Picture::class,
     *     mappedBy="tags"
     * )
     * @var Collection|Picture[]
     */
    private Collection $pictures;

    /**
     * @param string $name
     * @param array  $pictures
     */
    public function __construct(string $name, array $pictures = [])
    {
        $this->name = $name;
        foreach ($pictures as $picture) {
            if (!$picture instanceof Picture) {
                throw new DomainException('Некорректный формат данных о художниках');
            }
        }
        $this->pictures = new ArrayCollection($pictures);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Picture[]
     */
    public function getPictures(): array
    {
        return $this->pictures->toArray();
    }
}