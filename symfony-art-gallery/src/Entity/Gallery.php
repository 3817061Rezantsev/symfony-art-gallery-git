<?php

namespace Sergo\ArtGallery\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sergo\ArtGallery\Exception;

/**
 * Картинная галерея
 * @ORM\Entity()
 * @ORM\Table(
 *     name="galleries",
 *     indexes={
 *       @ORM\Index(name="galleries_name_idx", columns={"name"}),
 *       @ORM\Index(name="galleries_address_idx", columns={"address"})
 *     }
 * )
 */
class Gallery
{
    /**
     * ID галереи
     *
     * @var int
     * @ORM\Id()
     * @ORM\Column(name="id", type="integer", nullable=false)
     */
    private int $id;

    /**
     * Имя галереи
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @var string
     */
    private string $name;

    /**
     * Адрес галереи
     * @ORM\Column(name="address", type="string", length=255, nullable=false)
     * @var string
     */
    private string $address;

    /**
     * @ORM\OneToMany(
     *     targetEntity=\Sergo\ArtGallery\Entity\Ticket::class,
     *     mappedBy="gallery"
     * )
     * @var Collection| Ticket[]
     */
    private Collection $tickets;


    /*------------------------------------------------------*/
    /**
     * @param int    $id      - ID галереи
     * @param string $name    - Имя галереи
     * @param string $address - Адрес галереи
     */
    public function __construct(int $id, string $name, string $address, array $tickets = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        foreach ($tickets as $ticket) {
            if (!$ticket instanceof Ticket) {
                throw new Exception\DomainException('Некорректный формат данных о билетах');
            }
        }
        $this->tickets = new ArrayCollection($tickets);
    }


    /**
     * Получить ID галереи
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Получить имя галереи
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


    /**
     * Получить адрес галереи
     *
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTickets(): array
    {
        return $this->tickets->toArray();
    }
}
