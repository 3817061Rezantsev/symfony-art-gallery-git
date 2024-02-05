<?php

namespace Sergo\ArtGallery\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sergo\ArtGallery\Exception\RuntimeException;
use Sergo\ArtGallery\Infrastructure\Auth\HttpAuthProvider;
use Sergo\ArtGallery\Infrastructure\Controller\ControllerInterface;
use Sergo\ArtGallery\Infrastructure\Db\ConnectionInterface;
use Sergo\ArtGallery\Infrastructure\Http\ServerResponseFactory;
use Psr\Log\LoggerInterface;
use Sergo\ArtGallery\Infrastructure\ViewTemplate\ViewTemplateInterface;
use Sergo\ArtGallery\Service\ArrivalNewTicketReportService;
use Sergo\ArtGallery\Service\SearchTicketReportsService;
use Sergo\ArtGallery\Service\SearchTicketReportsService\SearchTicketReportCriteria;
use Sergo\ArtGallery\Service\SearchTicketsService;
use Sergo\ArtGallery\Service\SearchVisitorsService;
use Throwable;

class TicketReportAdministrationController implements ControllerInterface
{
    /**
     * @var LoggerInterface - логгер
     */
    private LoggerInterface $logger;
    /**
     * @var SearchVisitorsService - сервис поиска посетителей
     */
    private SearchVisitorsService $searchVisitorsService;
    /**
     * @var SearchTicketsService - сервис поиска билетов
     */
    private SearchTicketsService $searchTicketsService;
    /**
     * @var SearchTicketReportsService - сервис поиска актов о покупке билетов
     */
    private SearchTicketReportsService $searchTicketReportsService;
    /**
     * @var ViewTemplateInterface - Шаблонизатор для рендера HTML
     */
    private ViewTemplateInterface $viewTemplate;
    /**
     * @var ArrivalNewTicketReportService - прибытие нового билета
     */
    private ArrivalNewTicketReportService $arrivalNewTicketReportService;
    /**
     * Поставщик авторизации
     * @var HttpAuthProvider
     */
    private HttpAuthProvider $httpAuthProvider;
    /**
     * @var ServerResponseFactory - фабрика создания http ответа
     */
    private ServerResponseFactory $serverResponseFactory;
    /**
     * @var EntityManagerInterface - соединение с бд
     */
    private EntityManagerInterface $em;

    /**
     * @param LoggerInterface               $logger                        - логгер
     * @param SearchVisitorsService         $searchVisitorsService         - сервис поиска посетителей
     * @param SearchTicketsService          $searchTicketsService          - сервис поиска билетов
     * @param SearchTicketReportsService    $searchTicketReportsService    - сервис поиска актов о покупке билетов
     * @param ViewTemplateInterface         $viewTemplate                  - Шаблонизатор для рендера HTML
     * @param ArrivalNewTicketReportService $arrivalNewTicketReportService - прибытие нового билета
     * @param HttpAuthProvider              $httpAuthProvider              - Поставщик авторизации
     * @param ServerResponseFactory         $serverResponseFactory         - фабрика создания http ответа
     * @param EntityManagerInterface        $em                            - соединение с бд
     */
    public function __construct(
        LoggerInterface $logger,
        SearchVisitorsService $searchVisitorsService,
        SearchTicketsService $searchTicketsService,
        SearchTicketReportsService $searchTicketReportsService,
        ViewTemplateInterface $viewTemplate,
        ArrivalNewTicketReportService $arrivalNewTicketReportService,
        HttpAuthProvider $httpAuthProvider,
        ServerResponseFactory $serverResponseFactory,
        EntityManagerInterface $em
    ) {
        $this->logger = $logger;
        $this->searchVisitorsService = $searchVisitorsService;
        $this->searchTicketsService = $searchTicketsService;
        $this->searchTicketReportsService = $searchTicketReportsService;
        $this->viewTemplate = $viewTemplate;
        $this->arrivalNewTicketReportService = $arrivalNewTicketReportService;
        $this->httpAuthProvider = $httpAuthProvider;
        $this->serverResponseFactory = $serverResponseFactory;
        $this->em = $em;
    }


    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        try {
            if (false === $this->httpAuthProvider->isAuth()) {
                return $this->httpAuthProvider->doAuth($serverRequest->getUri());
            }

            $this->logger->info('run TicketReportAdministrationController::__invoke');
            $resultCreatingEntities = [];
            if ('POST' === $serverRequest->getMethod()) {
                $resultCreatingEntities = $this->creationOfTicketReport($serverRequest);
            }

            $dtoTickets = $this->searchTicketsService->search(new SearchTicketsService\SearchTicketsCriteria());
            $dtoVisitors = $this->searchVisitorsService->search(new SearchVisitorsService\SearchVisitorCriteria());
            $dtoTicketReports = $this->searchTicketReportsService->search(new SearchTicketReportCriteria());
            $viewData = [
                'tickets' => $dtoTickets,
                'visitors' => $dtoVisitors,
                'ticketReports' => $dtoTicketReports,
            ];
            $context = array_merge($viewData, $resultCreatingEntities);
            $template = 'ticketReport.administration.twig';
            $httpCode = 200;
        } catch (Throwable $e) {
            $context = [
                'errors' => [
                    $e->getMessage()
                ]
            ];
            $template = 'errors.twig';
            $httpCode = 500;
        }
        $html = $this->viewTemplate->render(
            $template,
            $context
        );
        return $this->serverResponseFactory->createHtmlResponse($httpCode, $html);
    }

    /**
     * Создание акта о покупке
     * @param ServerRequestInterface $serverRequest
     * @return array
     */
    private function creationOfTicketReport(ServerRequestInterface $serverRequest): array
    {
        $dataToCreate = [];
        parse_str($serverRequest->getBody(), $dataToCreate);
        $result = [];
        $result['formValidationResults'] = $this->validateData($dataToCreate);
        if (0 === count($result['formValidationResults'])) {
            $this->create($dataToCreate);
        } else {
            $result['ticketReportData'] = $dataToCreate;
        }

        return $result;
    }

    /**
     * Валидация данных
     * @param $dataToCreate
     * @return array
     */
    private function validateData($dataToCreate): array
    {
        $err = [];
        if (false === is_array($dataToCreate)) {
            $err[] = 'Объект должен быть массивом';
        } else {
            if (false === array_key_exists('dateOfPurchase', $dataToCreate)) {
                $err['dateOfPurchase'] = 'Нет даты покупки картины';
            } elseif (false === is_string($dataToCreate['dateOfPurchase'])) {
                $err['dateOfPurchase'] = 'Дата картины должна быть строкой';
            } elseif ('' === trim($dataToCreate['dateOfPurchase'])) {
                $err['dateOfPurchase'] = 'Дата картины не должна быть пустой';
            }
            $trimDate = trim($dataToCreate['dateOfPurchase']);
            $dateIsValid = 1 === preg_match('/^\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}$/', $trimDate);
            if (false === $dateIsValid) {
                $err['dateOfPurchase'] = 'Неверный формат даты';
            }

            if (false === array_key_exists('visitor_id', $dataToCreate)) {
                $err['visitor_id'] = 'Нет покупателя';
            }
            if (false === array_key_exists('ticket_id', $dataToCreate)) {
                $err['ticket_id'] = 'Нет билета';
            }
        }
        return $err;
    }

    /**
     * Добавление сущности в бд
     * @param array $requestData
     * @return void
     */
    private function create(array $requestData): void
    {
        try {
            $this->em->beginTransaction();
            $this->arrivalNewTicketReportService->registerTicketReport(
                new ArrivalNewTicketReportService\NewTicketReportDto(
                    (int)$requestData['visitor_id'],
                    (int)$requestData['ticket_id'],
                    $requestData['dateOfPurchase'],
                )
            );
            $this->em->flush();
            $this->em->commit();
        } catch (Throwable $exception) {
            $this->em->rollBack();
            throw new RuntimeException(
                'Picture creation error' . $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }
}
