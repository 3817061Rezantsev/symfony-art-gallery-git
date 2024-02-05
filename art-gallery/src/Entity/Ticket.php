<?php

namespace Sergo\ArtGallery\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Sergo\ArtGallery\Exception;
use Sergo\ArtGallery\ValueObject\Currency;

/**
 * Билет в галерею
 * @ORM\Entity(repositoryClass=\Sergo\ArtGallery\Repository\TicketDoctrineRepository::class)
 * @ORM\Table(
 *     name="tickets",
 *     indexes={
 *       @ORM\Index(name="tickets_cost_idx", columns={"cost"}),
 *       @ORM\Index(name="tickets_date_of_visit_idx", columns={"date_of_visit"})
 *     }
 * )
 */
class Ticket
{
    /**
     * Установить ID билета
     * @var int
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     */
    private int $id;
    /**
     * Установить галерею билета
     * @ORM\ManyToOne(targetEntity=\Sergo\ArtGallery\Entity\Gallery::class, inversedBy="tickets")
     * @ORM\JoinColumn(name="gallery_id", referencedColumnName="id")
     * @var Gallery
     */
    private Gallery $gallery;
    /**
     * Установить дату посещения
     * @ORM\Column(name="date_of_visit", type="date_immutable", nullable=false)
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $dateOfVisit;

    /**
     * @ORM\Column(name="cost", type="integer", nullable=false)
     * @var int - цена билета
     */
    private int $cost;

    /**
     * @ORM\ManyToOne(targetEntity=\Sergo\ArtGallery\ValueObject\Currency::class, fetch="EAGER")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
     * @var Currency - валюта
     */
    private Currency $currency;


    /*-------------------------------------------------------------*/


    /**
     * @param int               $id          - Установить ID билета
     * @param Gallery           $gallery     - Установить галерею билета
     * @param DateTimeImmutable $dateOfVisit - Установить дату посещения
     * @param int               $cost        - цена билета
     * @param Currency          $currency    - валюта
     */
    public function __construct(
        int $id,
        Gallery $gallery,
        DateTimeImmutable $dateOfVisit,
        int $cost,
        Currency $currency
    ) {
        $this->id = $id;
        $this->gallery = $gallery;
        $this->dateOfVisit = $dateOfVisit;
        $this->cost = $cost;
        $this->currency = $currency;
    }

    /**
     * получить цену билета
     * @return int
     */
    public function getCost(): int
    {
        return $this->cost;
    }

    /**
     * получить валюту
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }


    /**
     * Получить ID билета
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * Получить дату посещения
     *
     * @return DateTimeImmutable
     */
    public function getDateOfVisit(): DateTimeImmutable
    {
        return $this->dateOfVisit;
    }


    /**
     * Полусить номер галереи
     *
     * @return Gallery
     */
    public function getGallery(): Gallery
    {
        return $this->gallery;
    }

    /**
     * Создаёт сущность из массива
     *
     * @param array $data
     * @return Ticket
     */
    public static function createFromArray(array $data): Ticket
    {
        $requiredFields = [
            'id',
            'gallery_id',
            'dateOfVisit',
            'cost',
            'currency'
        ];

        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0) {
            $errMsg = sprintf("Отсутствуют обзательные элементы: %s", implode(',', $missingFields));
            throw new Exception\InvalidDataStructureException($errMsg);
        }

        return new Ticket($data['id'], $data['gallery_id'], $data['dateOfVisit'], $data['cost'], $data['currency']);
    }
}
