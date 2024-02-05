<?php

namespace Sergo\ArtGallery\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sergo\ArtGallery\Service\ArrivalNewPictureService;
use Sergo\ArtGallery\Service\ArrivalNewPictureService\ResultRegisteringPictureDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Throwable;

/**
 *  Контроллер для создания новых картин
 */
class CreateRegisterPictureController extends AbstractController
{
    /**
     * @var ValidatorInterface - сервис валидации
     */
    private ValidatorInterface $validator;

    /**
     * @var ArrivalNewPictureService - сервис для добавления новых картин
     */
    private ArrivalNewPictureService $arrivalNewPictureService;
    /**
     * @var EntityManagerInterface - соединение с бд
     */
    private EntityManagerInterface $em;


    /**
     * @param ArrivalNewPictureService $arrivalNewPictureService - сервис для добавления новых картин
     * @param EntityManagerInterface   $em                       - соединение с бд
     * @param ValidatorInterface       $validator                - сервис валидации
     */
    public function __construct(
        ArrivalNewPictureService $arrivalNewPictureService,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ) {
        $this->arrivalNewPictureService = $arrivalNewPictureService;
        $this->em = $em;
        $this->validator = $validator;
    }

    /**
     *
     * @param Request $serverRequest
     * @return Response
     */
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
     * @throws Exception
     */
    private function validateData($requestData): array
    {
        $constraint = [
            new Assert\Type(['type' => 'array', 'message' => 'Данные о новой картине должны быть массивом']),
            new Assert\Collection(
                [
                    'allowExtraFields'     => false,
                    'allowMissingFields'   => false,
                    'extraFieldsMessage'   => 'Есть лишнее поле {{ field }}',
                    'missingFieldsMessage' => 'Отсутствует обязательное поле {{ field }}',
                    'fields'               => [
                        'name'       => [
                            new Assert\Type(['type' => 'string', 'message' => 'Имя картины должно быть строкой']),
                            new Assert\NotBlank(['message' => 'Имя картины отсутствует', 'normalizer' => 'trim']),
                            new Assert\Length(['min' => 1, 'max' => 255]),
                        ],
                        'year'       => [
                            new Assert\Type(
                                ['type' => 'string', 'message' => 'Дата написания картины должна быть строкой']
                            ),
                            new Assert\NotBlank(['message' => 'Год написания отсутствует', 'normalizer' => 'trim']),
                            new Assert\Length(['min' => 1, 'max' => 4]),
                        ],
                        'painter_id' => [
                            new Assert\Type(['type' => 'int', 'message' => 'Id художника должно быть числом']),
                            new Assert\NotBlank(['message' => 'Id художника написания отсутствует']),
                        ]
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
