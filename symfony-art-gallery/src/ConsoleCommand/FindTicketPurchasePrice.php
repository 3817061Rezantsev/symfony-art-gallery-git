<?php

namespace Sergo\ArtGallery\ConsoleCommand;

use Sergo\ArtGallery\Service\SearchTicketReportsService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FindTicketPurchasePrice extends Command
{
    /**
     * @var SearchTicketReportsService - поиск данных об актах о покупке билетов
     */
    private SearchTicketReportsService $searchTicketReportsService;

    /**
     * Конструктор
     * @param SearchTicketReportsService $searchTicketReportsService - поиск данных об актах о покупке билетов
     */
    public function __construct(SearchTicketReportsService $searchTicketReportsService)
    {
        $this->searchTicketReportsService = $searchTicketReportsService;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('art-gallery:find-ticket-purchase-report');
        $this->setDescription('Found ticket-purchase-report');
        $this->setHelp('Find ticket-purchase-report by criteria');
        $this->addOption('visitor_fullName', 's', InputOption::VALUE_REQUIRED, 'Found visitor full name');
        $this->addOption('id', 'i', InputOption::VALUE_REQUIRED, 'Found Id');
        $this->addOption('ticket_gallery_name', 'N', InputOption::VALUE_REQUIRED, 'Found ticket gallery name');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $params = $input->getOptions();
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
        $output->writeln(json_encode($jsonData, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return self::SUCCESS;
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