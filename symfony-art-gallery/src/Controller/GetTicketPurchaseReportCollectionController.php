<?php

namespace Sergo\ArtGallery\Controller;

use Exception;
use Psr\Log\LoggerInterface;
use Sergo\ArtGallery\Service\SearchTicketReportsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Контроллер для получения данных об актах о покупке билетов
 */
class GetTicketPurchaseReportCollectionController extends AbstractController
{
    /**
     * @var ValidatorInterface сервис валидации
     */
    private ValidatorInterface $validator;

    /**
     * @var LoggerInterface - логгер
     */
    private LoggerInterface $logger;

    /**
     * @var SearchTicketReportsService - сервис поиска данных об актах о покупке билетов
     */
    private SearchTicketReportsService $searchTicketReportsService;

    /**
     * @param LoggerInterface            $logger                     - логгер
     * @param SearchTicketReportsService $searchTicketReportsService - сервис поиска данных об актах о покупке билетов
     * @param ValidatorInterface         $validator                  - сервис валидации
     */
    public function __construct(
        LoggerInterface $logger,
        SearchTicketReportsService $searchTicketReportsService,
        ValidatorInterface $validator
    ) {
        $this->logger = $logger;
        $this->searchTicketReportsService = $searchTicketReportsService;
        $this->validator = $validator;
    }

    /**
     * Валидирует параметры запроса
     * @param Request $serverRequest - объект серверного http запроса
     * @return string|null - строка с ошибкой или нулл если ошибки нет
     * @throws Exception
     */
    private function validateQueryParams(Request $serverRequest): ?string
    {
        $queryParams = array_merge($serverRequest->query->all(), $serverRequest->attributes->all());
        $constraint = new Assert\Collection(
            [
                'allowExtraFields'   => true,
                'allowMissingFields' => false,
                'fields'             => [
                    'id'                      => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect id']),
                    ]),
                    'ticket_id'               => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect ticket_id']),
                    ]),
                    'visitor_id'              => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect visitor_id']),
                    ]),
                    'visitor_fullName'        => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect visitor_fullName']),
                    ]),
                    'visitor_dateOfBirth'     => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect visitor_dateOfBirth']),
                    ]),
                    'visitor_telephoneNumber' => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect visitor_telephoneNumber']),
                    ]),
                    'ticket_dateOfVisit'      => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect ticket_dateOfVisit']),
                    ]),
                    'ticket_gallery_name'     => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect ticket_gallery_name']),
                    ]),
                    'ticket_gallery_address'  => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect ticket_gallery_address']),
                    ]),
                    'ticket_gallery_id'       => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect ticket_gallery_id']),
                    ]),
                    'cost'                    => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect cost']),
                    ]),
                    'currency'                => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect currency']),
                    ]),
                    'dateOfPurchase'          => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect dateOfPurchase']),
                    ]),

                ]
            ]
        );
        $errors = $this->validator->validate($queryParams, $constraint);
        $errStrCollection = array_map(static function ($v) {
            return $v->getMessage();
        }, $errors->getIterator()->getArrayCopy());
        return count($errStrCollection) > 0 ? implode(', ', $errStrCollection) : null;
    }

    /**
     * @throws Exception
     */
    public function __invoke(Request $serverRequest): Response
    {
        $this->logger->info("Ветка билето-репорты");
        $resultOfParamValidation = $this->validateQueryParams($serverRequest);

        if (null === $resultOfParamValidation) {
            $params = array_merge($serverRequest->query->all(), $serverRequest->attributes->all());
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
        return $this->json($result, $httpCode);
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
