<?php

namespace Sergo\ArtGallery\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;


/**
 *
 * @ORM\Entity
 */
class NFTArt extends Picture
{
    /**
     * @ORM\Column(name="nft_token", type="string", length=255, nullable=true)
     * @var string
     */
    private string $NFTToken;

    public function __construct(
        $NFTToken,
        int $id,
        string $name,
        Painter $painter,
        DateTimeImmutable $year,
        array $tags = []
    ) {
        $this->NFTToken = $NFTToken;
        parent::__construct($id, $name, $painter, $year, $tags);
    }


    /**
     * @return string
     */
    public function getNFTToken(): string
    {
        return $this->NFTToken;
    }
}