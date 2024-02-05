<?php

namespace Sergo\ArtGallery\Controller;

use Exception;
use Psr\Log\LoggerInterface;
use Sergo\ArtGallery\Service\SearchPictureReportsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Контроллер для получения данных об актах о покупке картин
 */
class GetPicturePurchaseReportCollectionController extends AbstractController
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
     * @var SearchPictureReportsService - сервис для нахождения данных об актах о покупке картин
     */
    private SearchPictureReportsService $searchPictureReportsService;


    /**
     * @param LoggerInterface             $logger                      - логгер
     * @param SearchPictureReportsService $searchPictureReportsService - сервис для нахождения данных об актах о покупке
     * @param ValidatorInterface          $validator                   - сервис валидации
     */
    public function __construct(
        LoggerInterface $logger,
        SearchPictureReportsService $searchPictureReportsService,
        ValidatorInterface $validator
    ) {
        $this->logger = $logger;
        $this->searchPictureReportsService = $searchPictureReportsService;
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
                    'id'                              => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect id']),
                    ]),
                    'picture_id'                      => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect picture_id']),
                    ]),
                    'visitor_id'                      => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect visitor_id']),
                    ]),
                    'visitor_fullName'                => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect visitor_fullName']),
                    ]),
                    'visitor_dateOfBirth'             => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect visitor_dateOfBirth']),
                    ]),
                    'visitor_telephoneNumber'         => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect visitor_telephoneNumber']),
                    ]),
                    'picture_painter_id'              => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect picture_painter_id']),
                    ]),
                    'picture_painter_fullName'        => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect picture_painter_fullName']),
                    ]),
                    'picture_painter_dateOfBirth'     => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect picture_painter_dateOfBirth']),
                    ]),
                    'picture_painter_telephoneNumber' => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect picture_painter_telephoneNumber']),
                    ]),
                    'picture_name'                    => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect picture_name']),
                    ]),
                    'picture_year'                    => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect picture_year']),
                    ]),
                    'cost'                            => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect cost']),
                    ]),
                    'currency'                        => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect currency']),
                    ]),
                    'dateOfPurchase'                  => new Assert\Optional([
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
        $this->logger->info("Ветка картино-репорты");
        $resultOfParamValidation = $this->validateQueryParams($serverRequest);

        if (null === $resultOfParamValidation) {
            $params = array_merge($serverRequest->query->all(), $serverRequest->attributes->all());
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
        return $this->json($result, $httpCode);
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
