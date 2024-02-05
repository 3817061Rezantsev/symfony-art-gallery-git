<?php

namespace Sergo\ArtGallery\Service\ArrivalNewPictureService;

class NewNFTArtDto extends NewPictureDto
{
    private string $NFTToken;

    public function __construct(string $name, int $painterId, string $year, string $NFTToken)
    {
        $this->NFTToken = $NFTToken;
        parent::__construct($name, $painterId, $year);
    }


    /**
     * @return string
     */
    public function getNFTToken(): string
    {
        return $this->NFTToken;
    }


}