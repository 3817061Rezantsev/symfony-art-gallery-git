<?php

namespace Sergo\ArtGallery\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sergo\ArtGallery\Infrastructure\Controller\ControllerInterface;
use Sergo\ArtGallery\Infrastructure\Db\ConnectionInterface;
use Sergo\ArtGallery\Infrastructure\Http\ServerResponseFactory;
use Sergo\ArtGallery\Service\ArrivalNewTicketReportService;
use Throwable;

/**
 * Контроллер для создания актов о покупки билетов
 */
class CreateRegisterTicketPurchaseReportController implements ControllerInterface
{
    /**
     * @var ArrivalNewTicketReportService - сервис для добавления актов о покупки билетов
     */
    private ArrivalNewTicketReportService $arrivalNewTicketReportService;
    /**
     * @var ServerResponseFactory - фабрика для создания http ответа
     */
    private ServerResponseFactory $serverResponseFactory;
    /**
     * @var EntityManagerInterface - соединение с бд
     */
    private EntityManagerInterface $em;

    /**
     * @param ArrivalNewTicketReportService $arrivalNewTicketReportService - сервис для добавления актов о покупки
     * @param ServerResponseFactory         $serverResponseFactory         - фабрика для создания http ответа
     * @param EntityManagerInterface        $em                            - соединение с бд
     */
    public function __construct(
        ArrivalNewTicketReportService $arrivalNewTicketReportService,
        ServerResponseFactory $serverResponseFactory,
        EntityManagerInterface $em
    ) {
        $this->arrivalNewTicketReportService = $arrivalNewTicketReportService;
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
     * @return ArrivalNewTicketReportService\ResultRegisteringTicketReportDto
     * @throws Exception
     */
    private function runService(array $requestData): ArrivalNewTicketReportService\ResultRegisteringTicketReportDto
    {
        $requestDto = new ArrivalNewTicketReportService\NewTicketReportDto(
            $requestData['visitor_id'],
            $requestData['ticket_id'],
            $requestData['dateOfPurchase']
        );
        return $this->arrivalNewTicketReportService->registerTicketReport($requestDto);
    }

    /**
     * формирование полученных данных в нужном формате
     * @param ArrivalNewTicketReportService\ResultRegisteringTicketReportDto $responseDto
     * @return array
     */
    private function buildJsonData(ArrivalNewTicketReportService\ResultRegisteringTicketReportDto $responseDto): array
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

            if (false === array_key_exists('visitor_id', $requestData)) {
                $err[] = 'No visitor_id';
            } elseif (false === is_int($requestData['visitor_id'])) {
                $err[] = 'visitor_id is not int';
            }

            if (false === array_key_exists('ticket_id', $requestData)) {
                $err[] = 'No ticket_id';
            } elseif (false === is_int($requestData['ticket_id'])) {
                $err[] = 'ticket_id is not int';
            }
        }
        return $err;
    }
}
