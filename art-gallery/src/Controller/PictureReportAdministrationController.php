<?php

namespace Sergo\ArtGallery\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sergo\ArtGallery\Exception\RuntimeException;
use Sergo\ArtGallery\Infrastructure\Auth\HttpAuthProvider;
use Sergo\ArtGallery\Infrastructure\Controller\ControllerInterface;
use Sergo\ArtGallery\Infrastructure\Db\ConnectionInterface;
use Sergo\ArtGallery\Infrastructure\Http\ServerResponseFactory;
use Psr\Log\LoggerInterface;
use Sergo\ArtGallery\Infrastructure\ViewTemplate\ViewTemplateInterface;
use Sergo\ArtGallery\Service\ArrivalNewPictureReportService;
use Sergo\ArtGallery\Service\SearchPictureReportsService;
use Sergo\ArtGallery\Service\SearchPictureReportsService\SearchPictureReportsCriteria;
use Sergo\ArtGallery\Service\SearchPicturesService;
use Sergo\ArtGallery\Service\SearchVisitorsService;
use Throwable;

class PictureReportAdministrationController implements ControllerInterface
{
    /**
     * Коды валюты, необходимые для создания шаблона
     */
    private const CURRENCY_CODS = [
        'RUB',
        'EUR',
        'USD'
    ];
    /**
     * @var LoggerInterface - логгер
     */
    private LoggerInterface $logger;
    /**
     * @var SearchVisitorsService - сервис поиска посетителей
     */
    private SearchVisitorsService $searchVisitorsService;
    /**
     * @var SearchPicturesService - сервис поиска картин
     */
    private SearchPicturesService $searchPicturesService;
    /**
     * @var SearchPictureReportsService - сервис поиска актов о покупке картин
     */
    private SearchPictureReportsService $searchPictureReportsService;
    /**
     * @var ViewTemplateInterface - Шаблонизатор для рендера HTML
     */
    private ViewTemplateInterface $viewTemplate;
    /**
     * @var ArrivalNewPictureReportService - сервис добавления новых картин
     */
    private ArrivalNewPictureReportService $arrivalNewPictureReportService;
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
     * @param LoggerInterface                $logger                         - логгер
     * @param SearchVisitorsService          $searchVisitorsService          - сервис поиска посетителей
     * @param SearchPicturesService          $searchPicturesService          - сервис поиска картин
     * @param SearchPictureReportsService    $searchPictureReportsService    - сервис поиска актов о покупке картин
     * @param ViewTemplateInterface          $viewTemplate                   - Шаблонизатор для рендера HTML
     * @param ArrivalNewPictureReportService $arrivalNewPictureReportService - сервис добавления новых картин
     * @param HttpAuthProvider               $httpAuthProvider               - Поставщик авторизации
     * @param ServerResponseFactory          $serverResponseFactory          - фабрика создания http ответа
     * @param EntityManagerInterface         $em                             - соединение с бд
     */
    public function __construct(
        LoggerInterface $logger,
        SearchVisitorsService $searchVisitorsService,
        SearchPicturesService $searchPicturesService,
        SearchPictureReportsService $searchPictureReportsService,
        ViewTemplateInterface $viewTemplate,
        ArrivalNewPictureReportService $arrivalNewPictureReportService,
        HttpAuthProvider $httpAuthProvider,
        ServerResponseFactory $serverResponseFactory,
        EntityManagerInterface $em
    ) {
        $this->logger = $logger;
        $this->searchVisitorsService = $searchVisitorsService;
        $this->searchPicturesService = $searchPicturesService;
        $this->searchPictureReportsService = $searchPictureReportsService;
        $this->viewTemplate = $viewTemplate;
        $this->arrivalNewPictureReportService = $arrivalNewPictureReportService;
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

            $this->logger->info('run PictureReportAdministrationController::__invoke');
            $resultCreatingEntities = [];
            if ('POST' === $serverRequest->getMethod()) {
                $resultCreatingEntities = $this->creationOfPictureReport($serverRequest);
            }

            $dtoPictures = $this->searchPicturesService->search(new SearchPicturesService\SearchPicturesCriteria());
            $dtoVisitors = $this->searchVisitorsService->search(new SearchVisitorsService\SearchVisitorCriteria());
            $dtoPictureReports = $this->searchPictureReportsService->search(new SearchPictureReportsCriteria());
            $viewData = [
                'pictures' => $dtoPictures,
                'visitors' => $dtoVisitors,
                'pictureReports' => $dtoPictureReports,
                'currencyCods' => self::CURRENCY_CODS,
            ];
            $context = array_merge($viewData, $resultCreatingEntities);
            $template = 'pictureReport.administration.twig';
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
    private function creationOfPictureReport(ServerRequestInterface $serverRequest): array
    {
        $dataToCreate = [];
        parse_str($serverRequest->getBody(), $dataToCreate);
        $result = [];
        $result['formValidationResults'] = $this->validateData($dataToCreate);
        if (0 === count($result['formValidationResults'])) {
            $this->create($dataToCreate);
        } else {
            $result['pictureReportData'] = $dataToCreate;
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
            if (false === array_key_exists('cost', $dataToCreate)) {
                $err['cost'] = 'Нет цены картины';
            } elseif (false === is_string($dataToCreate['cost'])) {
                $err['cost'] = 'Цена - не строка';
            } elseif ('' === trim($dataToCreate['cost'])) {
                $err['cost'] = 'Цена - пустая строка';
            }

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
            if (false === array_key_exists('picture_id', $dataToCreate)) {
                $err['picture_id'] = 'Нет картины';
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
            $this->arrivalNewPictureReportService->registerPictureReport(
                new ArrivalNewPictureReportService\NewPictureReportDto(
                    (int)$requestData['visitor_id'],
                    (int)$requestData['picture_id'],
                    $requestData['dateOfPurchase'],
                    (int)$requestData['cost'],
                    $requestData['currency'],
                )
            );
            $this->em->flush();
            $this->em->commit();
        } catch (Throwable $exception) {
            $this->em->rollBack();
            throw new RuntimeException(
                'Picture purchase report creation error' . $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }
}
