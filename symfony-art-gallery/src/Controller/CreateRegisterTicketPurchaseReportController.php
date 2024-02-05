<?php

namespace Sergo\ArtGallery\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sergo\ArtGallery\Service\ArrivalNewTicketReportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Throwable;

/**
 * Контроллер для создания актов о покупки билетов
 */
class CreateRegisterTicketPurchaseReportController extends AbstractController
{
    /**
     * @var ValidatorInterface сервис валидации
     */
    private ValidatorInterface $validator;

    /**
     * @var ArrivalNewTicketReportService - сервис для добавления актов о покупки билетов
     */
    private ArrivalNewTicketReportService $arrivalNewTicketReportService;

    /**
     * @var EntityManagerInterface - соединение с бд
     */
    private EntityManagerInterface $em;

    /**
     * @param ArrivalNewTicketReportService $arrivalNewTicketReportService - сервис для добавления актов о покупки
     * @param EntityManagerInterface        $em                            - соединение с бд
     * @param ValidatorInterface            $validator                     - сервис валидации
     */
    public function __construct(
        ArrivalNewTicketReportService $arrivalNewTicketReportService,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ) {
        $this->arrivalNewTicketReportService = $arrivalNewTicketReportService;
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
     * @throws Exception
     */
    private function validateData($requestData): array
    {
        $constraint = [
            new Assert\Type(['type' => 'array', 'message' => 'Данные о новой покупке билета должны быть массивом']),
            new Assert\Collection(
                [
                    'allowExtraFields'     => false,
                    'allowMissingFields'   => false,
                    'extraFieldsMessage'   => 'Есть лишнее поле {{ field }}',
                    'missingFieldsMessage' => 'Отсутствует обязательное поле {{ field }}',
                    'fields'               => [
                        'dateOfPurchase' => [
                            new Assert\Type(['type' => 'string', 'message' => 'Дата покупки билета должно быть строкой']
                            ),
                            new Assert\NotBlank(['message' => 'Дата покупки билета отсутствует', 'normalizer' => 'trim']
                            )
                        ],
                        'visitor_id'     => [
                            new Assert\Type(['type' => 'int', 'message' => 'Id покупателя должно быть числом']),
                            new Assert\NotBlank(['message' => 'Id покупателя написания отсутствует']),
                        ],
                        'ticket_id'      => [
                            new Assert\Type(['type' => 'int', 'message' => 'Id билета должно быть числом']),
                            new Assert\NotBlank(['message' => 'Id билета отсутствует']),
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
