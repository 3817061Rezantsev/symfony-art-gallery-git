<?php

namespace Sergo\ArtGallery\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sergo\ArtGallery\Infrastructure\Controller\ControllerInterface;
use Sergo\ArtGallery\Infrastructure\Db\ConnectionInterface;
use Sergo\ArtGallery\Infrastructure\Http\ServerResponseFactory;
use Sergo\ArtGallery\Service\ArrivalNewPictureService;
use Sergo\ArtGallery\Service\ArrivalNewPictureService\ResultRegisteringPictureDto;
use Throwable;

/**
 *  Контроллер для создания новых картин
 */
class CreateRegisterPictureController implements ControllerInterface
{
    /**
     * @var ArrivalNewPictureService - сервис для добавления новых картин
     */
    private ArrivalNewPictureService $arrivalNewPictureService;
    /**
     * @var ServerResponseFactory - фабрика для создания http ответа
     */
    private ServerResponseFactory $serverResponseFactory;
    /**
     * @var EntityManagerInterface - соединение с бд
     */
    private EntityManagerInterface $em;


    /**
     * @param ArrivalNewPictureService $arrivalNewPictureService - сервис для добавления новых картин
     * @param ServerResponseFactory    $serverResponseFactory    - фабрика для создания http ответа
     * @param EntityManagerInterface   $em                       - соединение с бд
     */
    public function __construct(
        ArrivalNewPictureService $arrivalNewPictureService,
        ServerResponseFactory $serverResponseFactory,
        EntityManagerInterface $em
    ) {
        $this->arrivalNewPictureService = $arrivalNewPictureService;
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
     * @return ResultRegisteringPictureDto
     */
    private function runService(array $requestData): ArrivalNewPictureService\ResultRegisteringPictureDto
    {
        $requestDto = new ArrivalNewPictureService\NewPictureDto(
            $requestData['name'],
            $requestData['painter_id'],
            $requestData['year']
        );
        return $this->arrivalNewPictureService->registerPicture($requestDto);
    }

    /**
     * формирование полученных данных в нужном формате
     * @param ResultRegisteringPictureDto $responseDto
     * @return array
     */
    private function buildJsonData(ResultRegisteringPictureDto $responseDto): array
    {
        return [
            'id' => $responseDto->getId(),
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
            if (false === array_key_exists('name', $requestData)) {
                $err[] = 'No name';
            } elseif (false === is_string($requestData['name'])) {
                $err[] = 'name is not string';
            } elseif ('' === trim($requestData['name'])) {
                $err[] = 'name is empty';
            }

            if (false === array_key_exists('year', $requestData)) {
                $err[] = 'No year';
            } elseif (false === is_string($requestData['year'])) {
                $err[] = 'year is not int';
            } elseif ('' === trim($requestData['year'])) {
                $err[] = 'year is empty';
            }

            if (false === array_key_exists('painter_id', $requestData)) {
                $err[] = 'No painter_id';
            } elseif (false === is_int($requestData['painter_id'])) {
                $err[] = 'painter_id is not int';
            }
        }
        return $err;
    }
}
