<?php

namespace Sergo\ArtGallery\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Sergo\ArtGallery\Exception;

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


    /*---------------------------------------------------------*/
    /**
     * @param int               $id      - Id картины
     * @param string            $name    - Имя картины
     * @param Painter           $painter - Автор картины
     * @param DateTimeImmutable $year    - Год написания картины
     */
    public function __construct(int $id, string $name, Painter $painter, DateTimeImmutable $year)
    {
        $this->id = $id;
        $this->name = $name;
        $this->painter = $painter;
        $this->year = $year;
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
     * Создаёт сущность из массива
     *
     * @param array $data
     * @return Picture
     */
    public static function createFromArray(array $data): Picture
    {
        $requiredFields = [
            'id',
            'name',
            'painter',
            'year'
        ];

        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0) {
            $errMsg = sprintf("Отсутствуют обзательные элементы: %s", implode(',', $missingFields));
            throw new Exception\InvalidDataStructureException($errMsg);
        }

        return new Picture($data['id'], $data['name'], $data['painter'], $data['year']);
    }
}
