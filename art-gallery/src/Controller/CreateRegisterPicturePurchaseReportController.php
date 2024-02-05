<?php

namespace Sergo\ArtGallery\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sergo\ArtGallery\Infrastructure\Controller\ControllerInterface;
use Sergo\ArtGallery\Infrastructure\Db\ConnectionInterface;
use Sergo\ArtGallery\Infrastructure\Http\ServerResponseFactory;
use Sergo\ArtGallery\Service\ArrivalNewPictureReportService;
use Throwable;

/**
 * Контроллер для создания новых актов о покупки картин
 */
class CreateRegisterPicturePurchaseReportController implements ControllerInterface
{
    /**
     * @var ArrivalNewPictureReportService - сервис для добавления новых актов о покупки картин
     */
    private ArrivalNewPictureReportService $arrivalNewPictureReportService;
    /**
     * @var ServerResponseFactory - фабрика для создания http ответа
     */
    private ServerResponseFactory $serverResponseFactory;
    /**
     * @var EntityManagerInterface - соединение с бд
     */
    private EntityManagerInterface $em;

    /**
     * @param ArrivalNewPictureReportService $arrivalNewPictureReportService - сервис для добавления актов о покупки
     * @param ServerResponseFactory          $serverResponseFactory          - фабрика для создания http ответа
     * @param EntityManagerInterface         $em                             - соединение с бд
     */
    public function __construct(
        ArrivalNewPictureReportService $arrivalNewPictureReportService,
        ServerResponseFactory $serverResponseFactory,
        EntityManagerInterface $em
    ) {
        $this->arrivalNewPictureReportService = $arrivalNewPictureReportService;
        $this->serverResponseFactory = $serverResponseFactory;
        $this->em = $em;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        try {
            $requestData = json_decode($serverRequest->getBody(), true, 512, JSON_THROW_ON_ERROR);
            $validationResult = $this->validateData($requestData);
            if (0 === count($validationResult)) {
                $this->em->beginTransaction();
                $responseDto = $this->runService($requestData);
                $this->em->flush();
                $this->em->commit();
                $httpCode = 201;
                $jsonData = $this->buildJsonData($responseDto);
            } else {
                $httpCode = 400;
                $jsonData = ['status' => 'fail', 'message' => implode(".", $validationResult)];
            }
        } catch (Throwable $e) {
            $this->em->rollBack();
            $httpCode = 500;
            $jsonData = ['status' => 'fail', 'message' => $e->getMessage()];
        }
        return $this->serverResponseFactory->createJsonResponse($httpCode, $jsonData);
    }

    /**
     * Запуск сервиса
     * @param array $requestData
     * @return ArrivalNewPictureReportService\ResultRegisteringPictureReportDto
     */
    private function runService(array $requestData): ArrivalNewPictureReportService\ResultRegisteringPictureReportDto
    {
        $requestDto = new ArrivalNewPictureReportService\NewPictureReportDto(
            $requestData['visitor_id'],
            $requestData['picture_id'],
            $requestData['dateOfPurchase'],
            $requestData['cost'],
            $requestData['currency'],
        );
        return $this->arrivalNewPictureReportService->registerPictureReport($requestDto);
    }

    /**
     * формирование полученных данных в нужном формате
     * @param ArrivalNewPictureReportService\ResultRegisteringPictureReportDto $responseDto
     * @return array
     */
    private function buildJsonData(ArrivalNewPictureReportService\ResultRegisteringPictureReportDto $responseDto): array
    {
        return [
            'id' => $responseDto->getId()
        ];
    }

    /**
     * Валидация данных
     * @param $requestData
     * @return array
     */
    private function validateData($requestData): array
    {
        $err = [];
        if (false === is_array($requestData)) {
            $err[] = 'This is not array';
        } else {
            if (false === array_key_exists('dateOfPurchase', $requestData)) {
                $err[] = 'No dateOfPurchase';
            } elseif (false === is_string($requestData['dateOfPurchase'])) {
                $err[] = 'dateOfPurchase is not string';
            } elseif ('' === trim($requestData['dateOfPurchase'])) {
                $err[] = 'dateOfPurchase is empty';
            }
            if (false === array_key_exists('currency', $requestData)) {
                $err[] = 'No currency';
            } elseif (false === is_string($requestData['currency'])) {
                $err[] = 'currency is not string';
            } elseif ('' === trim($requestData['currency'])) {
                $err[] = 'currency is empty';
            }

            if (false === array_key_exists('visitor_id', $requestData)) {
                $err[] = 'No visitor_id';
            } elseif (false === is_int($requestData['visitor_id'])) {
                $err[] = 'visitor_id is not int';
            }

            if (false === array_key_exists('picture_id', $requestData)) {
                $err[] = 'No picture_id';
            } elseif (false === is_int($requestData['picture_id'])) {
                $err[] = 'picture_id is not int';
            }
            if (false === array_key_exists('cost', $requestData)) {
                $err[] = 'No cost';
            } elseif (false === is_int($requestData['cost'])) {
                $err[] = 'cost is not int';
            }
        }
        return $err;
    }
}
