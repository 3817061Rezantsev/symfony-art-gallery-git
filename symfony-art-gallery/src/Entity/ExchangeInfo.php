<?php

namespace Sergo\ArtGallery\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=\Sergo\ArtGallery\Repository\ExchangeInfoDoctrineRepository::class)
 * @ORM\Table(
 *     name="exchange_info"
 * )
 */
class ExchangeInfo
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="exchange_info_id_seq")
     * @var int
     */
    private int $id;

    /**
     * @ORM\OneToOne(targetEntity=\Sergo\ArtGallery\Entity\PicturePurchaseReport::class)
     * @ORM\JoinColumn(name="first_purchase_id", referencedColumnName="id")
     * @var PicturePurchaseReport
     */
    private PicturePurchaseReport $firstCustomer;
    /**
     * @ORM\OneToOne(targetEntity=\Sergo\ArtGallery\Entity\PicturePurchaseReport::class)
     * @ORM\JoinColumn(name="second_purchase_id", referencedColumnName="id")
     * @var PicturePurchaseReport
     */
    private PicturePurchaseReport $secondCustomer;

    /**
     * @param int                   $id
     * @param PicturePurchaseReport $firstCustomer
     * @param PicturePurchaseReport $secondCustomer
     */
    public function __construct(int $id, PicturePurchaseReport $firstCustomer, PicturePurchaseReport $secondCustomer)
    {
        $this->id = $id;
        $this->firstCustomer = $firstCustomer;
        $this->secondCustomer = $secondCustomer;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return PicturePurchaseReport
     */
    public function getFirstCustomer(): PicturePurchaseReport
    {
        return $this->firstCustomer;
    }

    /**
     * @return PicturePurchaseReport
     */
    public function getSecondCustomer(): PicturePurchaseReport
    {
        return $this->secondCustomer;
    }
}