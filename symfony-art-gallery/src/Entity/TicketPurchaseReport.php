<?php

namespace Sergo\ArtGallery\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Sergo\ArtGallery\Exception;
use Sergo\ArtGallery\ValueObject\Currency;
use Sergo\ArtGallery\ValueObject\Money;
use Sergo\ArtGallery\ValueObject\PurchasePrice;

/**
 * Акт о покупке билета
 * @ORM\Entity(repositoryClass=\Sergo\ArtGallery\Repository\TicketPurchaseReportDoctrineRepository::class)
 * @ORM\Table(
 *     name="ticket_purchase_reports",
 *     indexes={
 *       @ORM\Index(name="ticket_purchase_reports_cost_idx", columns={"cost"}),
 *       @ORM\Index(name="ticket_purchase_reports_date_of_purchase_idx", columns={"date_of_purchase"})
 *     }
 * )
 */
class TicketPurchaseReport
{
    /**
     * ID отчета
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="ticket_purchase_reports_id_seq")
     * @var int
     */
    private int $id;

    /**
     * посетитель
     * @ORM\ManyToOne(targetEntity=\Sergo\ArtGallery\Entity\Visitor::class, inversedBy="ticketPurchaseReports")
     * @ORM\JoinColumn(name="visitor_id", referencedColumnName="id")
     * @var Visitor
     */
    private Visitor $visitor;

    /**
     * билет
     * @ORM\ManyToOne(targetEntity=\Sergo\ArtGallery\Entity\Ticket::class)
     * @ORM\JoinColumn(name="ticket_id", referencedColumnName="id")
     * @var Ticket
     */
    private Ticket $ticket;

    /**
     * Закупочная цена
     * @var ?PurchasePrice
     */
    private ?PurchasePrice $purchasePrice = null;

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


    /*-----------------------------------------------------------*/
    /**
     * @param int           $id            - ID
     * @param Visitor       $visitor       - посетитель
     * @param Ticket        $ticket        - билет
     * @param PurchasePrice $purchasePrice - закупочная цена
     */
    public function __construct(
        int $id,
        Visitor $visitor,
        Ticket $ticket,
        PurchasePrice $purchasePrice
    ) {
        $this->id = $id;
        $this->visitor = $visitor;
        $this->ticket = $ticket;
        $this->purchasePrice = $purchasePrice;
        $this->cost = $purchasePrice->getMoney()->getAmount();
        $this->currency = $purchasePrice->getMoney()->getCurrency();
        $this->dateOfPurchase = $purchasePrice->getDate();
    }


    /**
     * Получить ID очета
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
     * Получить посетителя
     *
     * @return Visitor
     */
    public function getVisitor(): Visitor
    {
        return $this->visitor;
    }

    /**
     * Получить билета
     *
     * @return Ticket
     */
    public function getTicket(): Ticket
    {
        return $this->ticket;
    }

    /**
     * Создаёт сущность из массива
     *
     * @param array $data
     * @return TicketPurchaseReport
     */
    public static function createFromArray(array $data): TicketPurchaseReport
    {
        $requiredFields = [
            'id',
            'visitor_id',
            'ticket_id',
            'purchasePrice'
        ];

        $missingFields = array_diff($requiredFields, array_keys($data));

        if (count($missingFields) > 0) {
            $errMsg = sprintf("Отсутствуют обзательные элементы: %s", implode(',', $missingFields));
            throw new Exception\InvalidDataStructureException($errMsg);
        }

        return new TicketPurchaseReport(
            $data['id'],
            $data['visitor_id'],
            $data['ticket_id'],
            $data['purchasePrice']
        );
    }
}
