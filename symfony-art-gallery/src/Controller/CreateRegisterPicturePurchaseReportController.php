<?php

namespace Sergo\ArtGallery\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sergo\ArtGallery\Service\ArrivalNewPictureReportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Throwable;

/**
 * Контроллер для создания новых актов о покупки картин
 */
class CreateRegisterPicturePurchaseReportController extends AbstractController
{
    /**
     * @var ValidatorInterface сервис валидации
     */
    private ValidatorInterface $validator;

    /**
     * @var ArrivalNewPictureReportService - сервис для добавления новых актов о покупки картин
     */
    private ArrivalNewPictureReportService $arrivalNewPictureReportService;
    /**
     * @var EntityManagerInterface - соединение с бд
     */
    private EntityManagerInterface $em;

    /**
     * @param ArrivalNewPictureReportService $arrivalNewPictureReportService - сервис для добавления актов о покупки
     * @param EntityManagerInterface         $em                             - соединение с бд
     * @param ValidatorInterface             $validator                      - сервис валидации
     */
    public function __construct(
        ArrivalNewPictureReportService $arrivalNewPictureReportService,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ) {
        $this->arrivalNewPictureReportService = $arrivalNewPictureReportService;
        $this->em = $em;
        $this->validator = $validator;
    }

    public function __invoke(Request $serverRequest): Response
    {
        try {
            $requestData = json_decode($serverRequest->getContent(), true, 512, JSON_THROW_ON_ERROR);
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
        return $this->json($jsonData, $httpCode);
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
                        'visitor_id'     => [
                            new Assert\Type(['type' => 'int', 'message' => 'Id покупателя должно быть числом']),
                            new Assert\NotBlank(['message' => 'Id покупателя написания отсутствует']),
                        ],
                        'picture_id'     => [
                            new Assert\Type(['type' => 'int', 'message' => 'Id картины должно быть числом']),
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
