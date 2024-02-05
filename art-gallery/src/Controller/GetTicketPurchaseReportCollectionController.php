<?php

namespace Sergo\ArtGallery\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sergo\ArtGallery\Infrastructure\Controller\ControllerInterface;
use Sergo\ArtGallery\Infrastructure\Http\ServerResponseFactory;
use Psr\Log\LoggerInterface;
use Sergo\ArtGallery\Infrastructure\Validator\Assert;
use Sergo\ArtGallery\Service\SearchTicketReportsService;

/**
 * Контроллер для получения данных об актах о покупке билетов
 */
class GetTicketPurchaseReportCollectionController implements ControllerInterface
{
    /**
     * @var LoggerInterface - логгер
     */
    private LoggerInterface $logger;

    /**
     * @var SearchTicketReportsService - сервис поиска данных об актах о покупке билетов
     */
    private SearchTicketReportsService $searchTicketReportsService;
    /**
     * @var ServerResponseFactory - фабрика для создания http ответа
     */
    private ServerResponseFactory $serverResponseFactory;

    /**
     * @param LoggerInterface            $logger                     - логгер
     * @param SearchTicketReportsService $searchTicketReportsService - сервис поиска данных об актах о покупке билетов
     * @param ServerResponseFactory      $serverResponseFactory      - фабрика для создания http ответа
     */
    public function __construct(
        LoggerInterface $logger,
        SearchTicketReportsService $searchTicketReportsService,
        ServerResponseFactory $serverResponseFactory
    ) {
        $this->logger = $logger;
        $this->searchTicketReportsService = $searchTicketReportsService;
        $this->serverResponseFactory = $serverResponseFactory;
    }

    /**
     * Валидирует параметры запроса
     * @param ServerRequestInterface $serverRequest - объект серверного http запроса
     * @return string|null - строка с ошибкой или нулл если ошибки нет
     */
    private function validateQueryParams(ServerRequestInterface $serverRequest): ?string
    {
        $paramsValidation = [
            'ticket_id'               => 'incorrect ticket_id',
            'visitor_id'              => 'incorrect visitor_id',
            'visitor_fullName'        => 'incorrect visitor_fullName',
            'visitor_dateOfBirth'     => 'incorrect visitor_dateOfBirth',
            'visitor_telephoneNumber' => 'incorrect visitor_telephoneNumber',
            'id'                      => 'incorrect id',
            'ticket_dateOfVisit'      => 'incorrect ticket_dateOfVisit',
            'ticket_gallery_name'     => 'incorrect ticket_gallery_name',
            'ticket_gallery_address'  => 'incorrect ticket_gallery_address',
            'ticket_gallery_id'       => 'incorrect ticket_gallery_id',
            'cost'                    => 'incorrect cost',
            'currency'                => 'incorrect currency',
            'dateOfPurchase'          => 'incorrect dateOfPurchase',
        ];
        $queryParams = array_merge($serverRequest->getQueryParams(), $serverRequest->getAttributes());

        return Assert::arrayElementsIsString($paramsValidation, $queryParams);
    }

    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $this->logger->info("Ветка билето-репорты");
        $resultOfParamValidation = $this->validateQueryParams($serverRequest);

        if (null === $resultOfParamValidation) {
            $params = array_merge($serverRequest->getQueryParams(), $serverRequest->getAttributes());
            $findTicketReports = $this->searchTicketReportsService->search(
                (new SearchTicketReportsService\SearchTicketReportCriteria())
                    ->setId(isset($params['id']) ? (int)$params['id'] : null)
                    ->setCost(isset($params['cost']) ? (int)$params['cost'] : null)
                    ->setCurrency($params["currency"] ?? null)
                    ->setDateOfPurchase($params["dateOfPurchase"] ?? null)
                    ->setVisitorId(isset($params['visitor_id']) ? (int)$params['visitor_id'] : null)
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
            $httpCode = $this->buildHttpCode($findTicketReports);
            $result = $this->buildResult($findTicketReports);
        } else {
            $httpCode = 500;
            $result = [
                'status'  => 'fail',
                'message' => $resultOfParamValidation
            ];
        }
        return $this->serverResponseFactory->createJsonResponse($httpCode, $result);
    }

    /**
     * Построение кода ответа
     * @param array $findTicketReports
     * @return int
     */
    protected function buildHttpCode(array $findTicketReports): int
    {
        return 200;
    }

    /**
     * Построение тела ответа
     * @param array $findTicketReports
     * @return array
     */
    protected function buildResult(array $findTicketReports): array
    {
        $result = [];
        foreach ($findTicketReports as $findTicketReport) {
            $result[] = $this->serializeTicketReport($findTicketReport);
        }
        return $result;
    }

    /**
     *  Сериализация данных
     * @param SearchTicketReportsService\TicketReportDto $findTicketRep
     * @return array
     */
    protected function serializeTicketReport(SearchTicketReportsService\TicketReportDto $findTicketRep): array
    {
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
        return [
            'id'             => $findTicketRep->getId(),
            'visitor'        => $visitor,
            'ticket'         => $ticket,
            'dateOfPurchase' => $findTicketRep->getDateOfPurchase(),
            'cost'           => $findTicketRep->getCost(),
            'currency'       => $findTicketRep->getCurrency()
        ];
    }
}
