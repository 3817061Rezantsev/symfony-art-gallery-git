<?php

namespace Sergo\ArtGallery\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sergo\ArtGallery\Service\ExchangePictureService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Throwable;

class ExchangePictureController extends AbstractController
{
    /**
     * @var ValidatorInterface сервис валидации
     */
    private ValidatorInterface $validator;
    private ExchangePictureService $exchangePictureService;
    /**
     * @var EntityManagerInterface - соединение с бд
     */
    private EntityManagerInterface $em;

    /**
     * @param ExchangePictureService $exchangePictureService
     * @param EntityManagerInterface $em
     * @param ValidatorInterface     $validator
     */
    public function __construct(
        ExchangePictureService $exchangePictureService,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    )
    {
        $this->exchangePictureService = $exchangePictureService;
        $this->em = $em;
        $this->validator = $validator;
    }

    public function __invoke(Request $serverRequest): Response
    {
        try {
            $requestData = json_decode($serverRequest->getContent(), true, 512, JSON_THROW_ON_ERROR);
            $validationResult = $this->validateData($requestData);;
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
        return $this->json($jsonData, $httpCode);
    }

    /**
     * Запуск сервиса
     * @param array $requestData
     * @return ExchangePictureService\ExchangedRegistrationDto
     */
    private function runService(array $requestData): ExchangePictureService\ExchangedRegistrationDto
    {
        $requestDto = new ExchangePictureService\ExchangeDataDto(
            $requestData['first_visitor_id'],
            $requestData['second_visitor_id'],
            $requestData['first_picture_id'],
            $requestData['second_picture_id'],
            $requestData['dateOfPurchase'],
            $requestData['cost'],
            $requestData['currency']
        );
        return $this->exchangePictureService->registerExchange($requestDto);
    }

    /**
     * формирование полученных данных в нужном формате
     * @param ExchangePictureService\ExchangedRegistrationDto $responseDto
     * @return array
     */
    private function buildJsonData(ExchangePictureService\ExchangedRegistrationDto $responseDto): array
    {
        return [
            'first_id' => $responseDto->getFirstId(),
            'second_id' => $responseDto->getSecondId(),
        ];
    }

    /**
     * Валидация данных
     * @param $requestData
     * @return array
     * @throws Exception
     */
    private function validateData($requestData): array
    {
        $constraint = [
            new Assert\Type(['type' => 'array', 'message' => 'Данные о новой покупке картины должны быть массивом']),
            new Assert\Collection(
                [
                    'allowExtraFields'     => false,
                    'allowMissingFields'   => false,
                    'extraFieldsMessage'   => 'Есть лишнее поле {{ field }}',
                    'missingFieldsMessage' => 'Отсутствует обязательное поле {{ field }}',
                    'fields'               => [
                        'dateOfPurchase' => [
                            new Assert\Type(
                                ['type' => 'string', 'message' => 'Дата покупки картины должно быть строкой']
                            ),
                            new Assert\NotBlank(
                                ['message' => 'Дата покупки картины отсутствует', 'normalizer' => 'trim']
                            ),
                            new Assert\Length(['min' => 1, 'max' => 255]),
                        ],
                        'currency'       => [
                            new Assert\Type(['type' => 'string', 'message' => 'Цена картины должна быть строкой']),
                            new Assert\NotBlank(['message' => 'Цена отсутствует', 'normalizer' => 'trim']),
                            new Assert\Length(['min' => 3, 'max' => 3]),
                        ],
                        'first_visitor_id'     => [
                            new Assert\Type(['type' => 'int', 'message' => 'Id покупателя 1 должно быть числом']),
                            new Assert\NotBlank(['message' => 'Id покупателя написания отсутствует']),
                        ],
                        'first_picture_id'     => [
                            new Assert\Type(['type' => 'int', 'message' => 'Id картины 1 должно быть числом']),
                            new Assert\NotBlank(['message' => 'Id картины отсутствует']),
                        ],
                        'second_visitor_id'     => [
                            new Assert\Type(['type' => 'int', 'message' => 'Id покупателя 2 должно быть числом']),
                            new Assert\NotBlank(['message' => 'Id покупателя написания отсутствует']),
                        ],
                        'second_picture_id'     => [
                            new Assert\Type(['type' => 'int', 'message' => 'Id картины 2 должно быть числом']),
                            new Assert\NotBlank(['message' => 'Id картины отсутствует']),
                        ],
                        'cost'           => [
                            new Assert\Type(['type' => 'int', 'message' => 'Цена должна быть числом']),
                            new Assert\NotBlank(['message' => 'Цена отсутствует']),
                        ],
                    ]
                ]
            )
        ];
        $errors = $this->validator->validate($requestData, $constraint);
        return array_map(static function ($v) {
            return $v->getMessage();
        }, $errors->getIterator()->getArrayCopy());
    }

}