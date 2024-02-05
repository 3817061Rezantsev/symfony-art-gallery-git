<?php

namespace Sergo\ArtGallery\Controller;


use Exception;
use Psr\Log\LoggerInterface;
use Sergo\ArtGallery\Service\SearchPicturesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Контроллер для просмотра данных о картинах
 */
class GetPictureCollectionController extends AbstractController
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
     * @var SearchPicturesService - сервис поиска картин
     */
    private SearchPicturesService $searchPicturesService;


    /**
     * @param LoggerInterface       $logger          - логгер
     * @param SearchPicturesService $picturesService - сервис поиска картин
     * @param ValidatorInterface    $validator       - сервис валидации
     */
    public function __construct(
        LoggerInterface $logger,
        SearchPicturesService $picturesService,
        ValidatorInterface $validator
    ) {
        $this->logger = $logger;
        $this->searchPicturesService = $picturesService;
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
                    'name'                    => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect name']),
                    ]),
                    'painter_id'              => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect painter_id']),
                    ]),
                    'painter_fullName'        => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect painter_fullName']),
                    ]),
                    'painter_dateOfBirth'     => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect painter_dateOfBirth']),
                    ]),
                    'painter_telephoneNumber' => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect painter_telephoneNumber']),
                    ]),
                    'year'                    => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect year']),
                    ]),
                    'start'                   => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect start']),
                    ]),
                    'end'                     => new Assert\Optional([
                        new Assert\Type(['type' => 'string', 'message' => 'incorrect end']),
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
        $this->logger->info("Ветка pictures");

        $resultOfParamValidation = $this->validateQueryParams($serverRequest);
        if (null === $resultOfParamValidation) {
            $params = array_merge($serverRequest->query->all(), $serverRequest->attributes->all());
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
        return $this->json($result, $httpCode);
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
