<?php

namespace Sergo\ArtGallery\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sergo\ArtGallery\Infrastructure\Controller\ControllerInterface;
use Sergo\ArtGallery\Infrastructure\Http\ServerResponseFactory;
use Psr\Log\LoggerInterface;
use Sergo\ArtGallery\Infrastructure\Validator\Assert;
use Sergo\ArtGallery\Service\SearchPicturesService;

/**
 * Контроллер для просмотра данных о картинах
 */
class GetPictureCollectionController implements ControllerInterface
{
    /**
     * @var LoggerInterface - логгер
     */
    private LoggerInterface $logger;

    /**
     * @var SearchPicturesService - сервис поиска картин
     */
    private SearchPicturesService $searchPicturesService;
    /**
     * @var ServerResponseFactory - фабрика для создания http ответа
     */
    private ServerResponseFactory $serverResponseFactory;

    /**
     * @param LoggerInterface       $logger                - логгер
     * @param SearchPicturesService $picturesService       - сервис поиска картин
     * @param ServerResponseFactory $serverResponseFactory - фабрика для создания http ответа
     */
    public function __construct(
        LoggerInterface $logger,
        SearchPicturesService $picturesService,
        ServerResponseFactory $serverResponseFactory
    ) {
        $this->logger = $logger;
        $this->searchPicturesService = $picturesService;
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
            'id'                      => 'incorrect id',
            'name'                    => 'incorrect name',
            'painter_id'              => 'incorrect painter_id',
            'painter_fullName'        => 'incorrect painter_fullName',
            'painter_dateOfBirth'     => 'incorrect painter_dateOfBirth',
            'painter_telephoneNumber' => 'incorrect painter_telephoneNumber',
            'year'                    => 'incorrect year',
            'start'                   => 'incorrect start',
            'end'                     => 'incorrect end',
        ];
        $queryParams = array_merge($serverRequest->getQueryParams(), $serverRequest->getAttributes());

        return Assert::arrayElementsIsString($paramsValidation, $queryParams);
    }


    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $this->logger->info("Ветка pictures");

        $resultOfParamValidation = $this->validateQueryParams($serverRequest);

        if (null === $resultOfParamValidation) {
            $params = array_merge($serverRequest->getQueryParams(), $serverRequest->getAttributes());
            $findPictures = $this->searchPicturesService->search(
                (new SearchPicturesService\SearchPicturesCriteria())
                    ->setId(isset($params['id']) ? (int)$params['id'] : null)
                    ->setName($params['name'] ?? null)
                    ->setPainterId(isset($params['painter_id']) ? (int)$params['painter_id'] : null)
                    ->setPainterFullName($params['painter_fullName'] ?? null)
                    ->setPainterDateOfBirth($params['painter_dateOfBirth'] ?? null)
                    ->setPainterTelephoneNumber($params['painter_telephoneNumber'] ?? null)
                    ->setYear($params['year'] ?? null)
                    ->setStart($params['start'] ?? null)
                    ->setEnd($params['end'] ?? null)
            );
            $httpCode = $this->buildHttpCode($findPictures);
            $result = $this->buildResult($findPictures);
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
     * @param array $findPictures
     * @return int
     */
    protected function buildHttpCode(array $findPictures): int
    {
        return 200;
    }

    /**
     * Построение тела ответа
     * @param array $findPictures
     * @return array
     */
    protected function buildResult(array $findPictures): array
    {
        $result = [];
        foreach ($findPictures as $findPicture) {
            $result[] = $this->serializePicture($findPicture);
        }
        return $result;
    }

    /**
     * Сериализация данных
     * @param SearchPicturesService\PictureDto $pictureDto
     * @return array
     */
    protected function serializePicture(SearchPicturesService\PictureDto $pictureDto): array
    {
        $painterDto = $pictureDto->getPainterDto();
        $painter = [
            'id'              => $painterDto->getId(),
            'fullName'        => $painterDto->getFullName(),
            'dateOfBirth'     => $painterDto->getDateOfBirth(),
            'telephoneNumber' => $painterDto->getTelephoneNumber()
        ];
        return [
            'id'      => $pictureDto->getId(),
            'name'    => $pictureDto->getName(),
            'painter' => $painter,
            'year'    => $pictureDto->getYear(),
        ];
    }
}
