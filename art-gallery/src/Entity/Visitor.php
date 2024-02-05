<?php

namespace Sergo\ArtGallery\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sergo\ArtGallery\Exception;
use Sergo\ArtGallery\Exception\DomainException;

/**
 * Посетитель
 * @ORM\Entity(repositoryClass=\Sergo\ArtGallery\Repository\VisitorDoctrineRepository::class)
 */
class Visitor extends AbstractUser
{
    /**
     * Купленные картины
     * @ORM\OneToMany(
     *     targetEntity=\Sergo\ArtGallery\Entity\PicturePurchaseReport::class,
     *     mappedBy="visitor"
     * )
     * @var Collection|PicturePurchaseReport[]
     */
    private Collection $picturePurchaseReports;
    /**
     * Купленные билеты
     * @ORM\OneToMany(
     *     targetEntity=\Sergo\ArtGallery\Entity\TicketPurchaseReport::class,
     *     mappedBy="visitor"
     * )
     * @var Collection|TicketPurchaseReport[]
     */
    private Collection $ticketPurchaseReports;

    public function __construct(
        int $id,
        string $fullName,
        DateTimeImmutable $dateOfBirth,
        string $telephoneNumber,
        array $picturePurchaseReports = [],
        array $ticketPurchaseReports = []
    ) {
        foreach ($picturePurchaseReports as $picturePurchaseReport) {
            if (!$picturePurchaseReport instanceof PicturePurchaseReport) {
                throw new DomainException('Некорректный формат данных о покупке картины');
            }
        }
        $this->picturePurchaseReports = new ArrayCollection($picturePurchaseReports);
        foreach ($ticketPurchaseReports as $ticketPurchaseReport) {
            if (!$ticketPurchaseReport instanceof PicturePurchaseReport) {
                throw new DomainException('Некорректный формат данных о покупке билета');
            }
        }
        $this->ticketPurchaseReports = new ArrayCollection($ticketPurchaseReports);
        parent::__construct($id, $fullName, $dateOfBirth, $telephoneNumber);
    }

    /**
     *
     * @return PicturePurchaseReport[]
     */
    public function getPicturePurchaseReports(): array
    {
        return $this->picturePurchaseReports->toArray();
    }

    /**
     *
     * @return TicketPurchaseReport[]
     */
    public function getTicketPurchaseReports(): array
    {
        return $this->ticketPurchaseReports->toArray();
    }


}
