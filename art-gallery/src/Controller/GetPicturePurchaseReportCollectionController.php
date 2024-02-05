<?php

namespace Sergo\ArtGallery\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sergo\ArtGallery\Infrastructure\Controller\ControllerInterface;
use Sergo\ArtGallery\Infrastructure\Http\ServerResponseFactory;
use Psr\Log\LoggerInterface;
use Sergo\ArtGallery\Infrastructure\Validator\Assert;
use Sergo\ArtGallery\Service\SearchPictureReportsService;

/**
 * Контроллер для получения данных об актах о покупке картин
 */
class GetPicturePurchaseReportCollectionController implements ControllerInterface
{
    /**
     * @var LoggerInterface - логгер
     */
    private LoggerInterface $logger;
    /**
     * @var SearchPictureReportsService - сервис для нахождения данных об актах о покупке картин
     */
    private SearchPictureReportsService $searchPictureReportsService;
    /**
     * @var ServerResponseFactory - фабрика для создания http ответа
     */
    private ServerResponseFactory $serverResponseFactory;

    /**
     * @param LoggerInterface             $logger                      - логгер
     * @param SearchPictureReportsService $searchPictureReportsService - сервис для нахождения данных об актах о покупке
     * @param ServerResponseFactory       $serverResponseFactory       - фабрика для создания http ответа
     */
    public function __construct(
        LoggerInterface $logger,
        SearchPictureReportsService $searchPictureReportsService,
        ServerResponseFactory $serverResponseFactory
    ) {
        $this->logger = $logger;
        $this->searchPictureReportsService = $searchPictureReportsService;
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
            'picture_id'                      => 'incorrect picture_id',
            'id'                              => 'incorrect id',
            'visitor_id'                      => 'incorrect visitor_id',
            'visitor_fullName'                => 'incorrect visitor_fullName',
            'visitor_dateOfBirth'             => 'incorrect visitor_dateOfBirth',
            'visitor_telephoneNumber'         => 'incorrect visitor_telephoneNumber',
            'picture_name'                    => 'incorrect picture_name',
            'picture_painter_id'              => 'incorrect painter_id',
            'picture_painter_fullName'        => 'incorrect painter_fullName',
            'picture_painter_dateOfBirth'     => 'incorrect painter_dateOfBirth',
            'picture_painter_telephoneNumber' => 'incorrect painter_telephoneNumber',
            'picture_year'                    => 'incorrect picture_year',
            'cost'                            => 'incorrect cost',
            'currency'                        => 'incorrect currency',
            'dateOfPurchase'                  => 'incorrect dateOfPurchase',
        ];
        $queryParams = array_merge($serverRequest->getQueryParams(), $serverRequest->getAttributes());

        return Assert::arrayElementsIsString($paramsValidation, $queryParams);
    }

    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $this->logger->info("Ветка картино-репорты");
        $resultOfParamValidation = $this->validateQueryParams($serverRequest);

        if (null === $resultOfParamValidation) {
            $params = array_merge($serverRequest->getQueryParams(), $serverRequest->getAttributes());
            $findPictureReport = $this->searchPictureReportsService->search(
                (new SearchPictureReportsService\SearchPictureReportsCriteria())
                    ->setId(isset($params['id']) ? (int)$params['id'] : null)
                    ->setCost(isset($params['cost']) ? (int)$params['cost'] : null)
                    ->setCurrency($params["currency"] ?? null)
                    ->setDateOfPurchase($params["dateOfPurchase"] ?? null)
                    ->setVisitorId(isset($params['visitor_id']) ? (int)$params['visitor_id'] : null)
                    ->setVisitorFullName($params["visitor_fullName"] ?? null)
                    ->setVisitorDateOfBirth($params["visitor_dateOfBirth"] ?? null)
                    ->setVisitorTelephoneNumber($params["visitor_telephoneNumber"] ?? null)
                    ->setPictureId(isset($params['picture_id']) ? (int)$params['picture_id'] : null)
                    ->setPictureName($params["picture_name"] ?? null)
                    ->setPicturePainterId(
                        isset($params['picture_painter_id']) ? (int)$params['picture_painter_id'] : null
                    )
                    ->setPicturePainterFullName($params["picture_painter_fullName"] ?? null)
                    ->setPicturePainterDateOfBirth($params["picture_painter_dateOfBirth"] ?? null)
                    ->setPicturePainterTelephoneNumber($params["picture_painter_telephoneNumber"] ?? null)
                    ->setPictureYear($params["picture_year"] ?? null)
            );
            $httpCode = $this->buildHttpCode($findPictureReport);
            $result = $this->buildResult($findPictureReport);
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
     * @param array $findPictureReport
     * @return int
     */
    protected function buildHttpCode(array $findPictureReport): int
    {
        return 200;
    }


    /**
     * Построение тела ответа
     * @param array $findPictureReport
     * @return array
     */
    protected function buildResult(array $findPictureReport): array
    {
        $result = [];
        foreach ($findPictureReport as $findPictureRep) {
            $result[] = $this->serializePictureReport($findPictureRep);
        }
        return $result;
    }

    /**
     * Сериализация данных
     * @param SearchPictureReportsService\PictureReportDto $findPictureRep
     * @return array
     */
    protected function serializePictureReport(SearchPictureReportsService\PictureReportDto $findPictureRep): array
    {
        $visitorDto = $findPictureRep->getVisitor();
        $visitor = [
            'id'              => $visitorDto->getId(),
            'fullName'        => $visitorDto->getFullName(),
            'dateOfBirth'     => $visitorDto->getDateOfBirth(),
            'telephoneNumber' => $visitorDto->getTelephoneNumber()
        ];
        $painterDto = $findPictureRep->getPicture()->getPainterDto();
        $painter = [
            'id'              => $painterDto->getId(),
            'fullName'        => $painterDto->getFullName(),
            'dateOfBirth'     => $painterDto->getDateOfBirth(),
            'telephoneNumber' => $painterDto->getTelephoneNumber()
        ];
        $pictureDto = $findPictureRep->getPicture();
        $picture = [
            'id'      => $pictureDto->getId(),
            'name'    => $pictureDto->getName(),
            'painter' => $painter,
            'year'    => $pictureDto->getYear(),
        ];

        return [
            'id'             => $findPictureRep->getId(),
            'visitor'        => $visitor,
            'picture'        => $picture,
            'dateOfPurchase' => $findPictureRep->getDateOfPurchase(),
            'cost'           => $findPictureRep->getCost(),
            'currency'       => $findPictureRep->getCurrency()
        ];
    }
}
