<?php

namespace Sergo\ArtGallery\ConsoleCommand;

use JsonException;
use Sergo\ArtGallery\Infrastructure\Console\CommandInterface;
use Sergo\ArtGallery\Infrastructure\Console\Output\OutputInterface;
use Sergo\ArtGallery\Service\SearchTicketReportsService;

/**
 *  Получение данных об актах о покупке билетов
 */
class FindTicketReports implements CommandInterface
{
    /**
     * Отвечает за вывод данных в консоль
     *
     * @var OutputInterface
     */
    private OutputInterface $output;

    /**
     * @var SearchTicketReportsService - поиск данных об актах о покупке билетов
     */
    private SearchTicketReportsService $searchTicketReportsService;

    /**
     * Конструктор
     * @param OutputInterface            $output                     - Отвечает за вывод данных в консоль
     * @param SearchTicketReportsService $searchTicketReportsService - поиск данных об актах о покупке билетов
     */
    public function __construct(OutputInterface $output, SearchTicketReportsService $searchTicketReportsService)
    {
        $this->output = $output;
        $this->searchTicketReportsService = $searchTicketReportsService;
    }


    /**
     * @inheritDoc
     */
    public static function getShortOptions(): string
    {
        return 'i::';
    }

    /**
     * @inheritDoc
     */
    public static function getLongOptions(): array
    {
        return [
            'id::',
        ];
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function __invoke(array $params): void
    {
        $dtoCollection = $this->searchTicketReportsService->search(
            (new SearchTicketReportsService\SearchTicketReportCriteria())->setId($params["id"] ?? null)
                ->setCost($params["cost"] ?? null)
                ->setCurrency($params["currency"] ?? null)
                ->setDateOfPurchase($params["dateOfPurchase"] ?? null)
                ->setVisitorId($params["visitor_id"] ?? null)
                ->setVisitorFullName($params["visitor_fullName"] ?? null)
                ->setVisitorDateOfBirth($params["visitor_dateOfBirth"] ?? null)
                ->setVisitorTelephoneNumber($params["visitor_telephoneNumber"] ?? null)
                ->setTicketId(isset($params['ticket_id']) ? (int)$params['ticket_id'] : null)
                ->setTicketCost(isset($params['ticket_cost']) ? (int)$params['ticket_cost'] : null)
                ->setTicketGalleryId($params["ticket_gallery_id"] ?? null)
                ->setTicketGalleryName($params["ticket_gallery_name"] ?? null)
                ->setTicketGalleryAddress($params["ticket_gallery_address"] ?? null)
                ->setTicketDateOfVisit($params["ticket_dateOfVisit"] ?? null)
                ->setTicketCurrency($params["ticket_currency"] ?? null)
        );
        $jsonData = $this->buildJsonData($dtoCollection);
        $this->output->print(json_encode($jsonData, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * формирование полученных данных в нужном формате
     * @param array $dtoCollection
     * @return array
     */
    private function buildJsonData(array $dtoCollection): array
    {
        $result = [];
        foreach ($dtoCollection as $findTicketRep) {
            $visitorDto = $findTicketRep->getVisitor();
            $visitor = [
                'id'              => $visitorDto->getId(),
                'fullName'        => $visitorDto->getFullName(),
                'dateOfBirth'     => $visitorDto->getDateOfBirth(),
                'telephoneNumber' => $visitorDto->getTelephoneNumber()
            ];
            $galleryDto = $findTicketRep->getTicket()->getGallery();
            $gallery = [
                'id'      => $galleryDto->getId(),
                'name'    => $galleryDto->getName(),
                'address' => $galleryDto->getAddress(),
            ];
            $ticketDto = $findTicketRep->getTicket();
            $ticket = [
                'id'          => $ticketDto->getId(),
                'gallery'     => $gallery,
                'dateOfVisit' => $ticketDto->getDateOfVisit(),
                'cost'        => $ticketDto->getCost(),
                'currency'    => $ticketDto->getCurrency()
            ];
            $jsonData = [
                'id'             => $findTicketRep->getId(),
                'visitor'        => $visitor,
                'ticket'         => $ticket,
                'dateOfPurchase' => $findTicketRep->getDateOfPurchase(),
                'cost'           => $findTicketRep->getCost(),
                'currency'       => $findTicketRep->getCurrency()
            ];
            $result[] = $jsonData;
        }
        return $result;
    }
}
