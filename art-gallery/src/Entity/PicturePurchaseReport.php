<?php

namespace Sergo\ArtGallery\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Sergo\ArtGallery\Exception;
use Sergo\ArtGallery\ValueObject\Currency;
use Sergo\ArtGallery\ValueObject\Money;
use Sergo\ArtGallery\ValueObject\PurchasePrice;

/**
 * @ORM\Entity(repositoryClass=\Sergo\ArtGallery\Repository\PicturePurchaseReportDoctrineRepository::class)
 * @ORM\Table(
 *     name="picture_purchase_reports",
 *     indexes={
 *      @ORM\Index(name="picture_purchase_reports_cost_idx", columns={"cost"}),
 *      @ORM\Index(name="picture_purchase_reports_date_of_purchase_idx", columns={"date_of_purchase"})
 *     }
 * )
 */
class PicturePurchaseReport
{
    /**
     * ID акта о покупке
     * @var int
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="picture_purchase_reports_id_seq")
     */
    private int $id;

    /**
     *  Посетитель, купивший картину
     * @ORM\ManyToOne(targetEntity=\Sergo\ArtGallery\Entity\Visitor::class, inversedBy="picturePurchaseReports")
     * @ORM\JoinColumn(name="visitor_id", referencedColumnName="id")
     * @var Visitor
     */
    private Visitor $visitor;

    /**
     * Проданная картина
     * @ORM\ManyToOne(targetEntity=\Sergo\ArtGallery\Entity\Picture::class)
     * @ORM\JoinColumn(name="picture_id", referencedColumnName="id")
     * @var Picture
     */
    private Picture $picture;

    /**
     * время, когда была получена инфа о цакуп цене
     * @ORM\Column(name="date_of_purchase", type="datetime_immutable", nullable=false)
     * @var DateTimeInterface
     */
    private DateTimeInterface $dateOfPurchase;

    /**
     * @ORM\Column(name="cost", type="integer", nullable=false)
     * @var int
     */
    private int $cost;

    /**
     * @ORM\ManyToOne(targetEntity=\Sergo\ArtGallery\ValueObject\Currency::class, fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="currency_id", referencedColumnName="id")
     * })
     * @var Currency
     */
    private Currency $currency;


    /**
     * Закупочная цена
     * @var ?PurchasePrice
     */
    private ?PurchasePrice $purchasePrice = null;

    /*----------------------------------------------------------------------*/
    /**
     * @param int           $id            - ID акта о покупке
     * @param Visitor       $visitor       - Посетитель, купивший картину
     * @param Picture       $picture       - Проданная картина
     * @param PurchasePrice $purchasePrice - Закупочная цена
     */
    public function __construct(int $id, Visitor $visitor, Picture $picture, PurchasePrice $purchasePrice)
    {
        $this->id = $id;
        $this->visitor = $visitor;
        $this->picture = $picture;
        $this->purchasePrice = $purchasePrice;
        $this->cost = $purchasePrice->getMoney()->getAmount();
        $this->currency = $purchasePrice->getMoney()->getCurrency();
        $this->dateOfPurchase = $purchasePrice->getDate();
    }

    /**
     * Получить ID отчета
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Получить номер посетителя
     *
     * @return Visitor
     */
    public function getVisitor(): Visitor
    {
        return $this->visitor;
    }

    /**
     * Получить номер картины
     *
     * @return Picture
     */
    public function getPicture(): Picture
    {
        return $this->picture;
    }

    /**
     * @return PurchasePrice
     */
    public function getPurchasePrice(): PurchasePrice
    {
        if (null === $this->purchasePrice) {
            $this->purchasePrice = new PurchasePrice(
                $this->dateOfPurchase,
                new Money(
                    $this->cost,
                    $this->currency
                )
            );
        }
        return $this->purchasePrice;
    }

    /**
     * Создаёт сущность из массива
     *
     * @param array $data
     * @return PicturePurchaseReport
     */
    public static function createFromArray(array $data): PicturePurchaseReport
    {
        $requiredFields = [
            'id',
            'visitor_id',
            'picture_id',
            'purchasePrice'
        ];

        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0) {
            $errMsg = sprintf("Отсутствуют обзательные элементы: %s", implode(',', $missingFields));
            throw new Exception\InvalidDataStructureException($errMsg);
        }

        return new PicturePurchaseReport(
            $data['id'],
            $data['visitor_id'],
            $data['picture_id'],
            $data['purchasePrice']
        );
    }
}
