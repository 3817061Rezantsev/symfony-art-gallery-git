<?php

namespace Sergo\ArtGallery\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     name="srcatches"
 * )
 */
class Srcatch
{

    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="srcatches_id_seq")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=\Sergo\ArtGallery\Entity\Painter::class, inversedBy="srcatches")
     * @ORM\JoinColumn(name="painter_id", referencedColumnName="id")
     * @var Painter
     */
    private Painter $painter;

    /**
     * @ORM\ManyToOne(targetEntity=\Sergo\ArtGallery\Entity\Picture::class, inversedBy="srcatches")
     * @ORM\JoinColumn(name="picture_id", referencedColumnName="id")
     * @var Picture
     */
    private Picture $picture;

    public function __construct(int $id, Painter $painter, Picture $picture)
    {
        $this->id = $id;
        $this->painter = $painter;
        $this->picture = $picture;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Painter
     */
    public function getPainter(): Painter
    {
        return $this->painter;
    }

    /**
     * @return Picture
     */
    public function getPicture(): Picture
    {
        return $this->picture;
    }


}