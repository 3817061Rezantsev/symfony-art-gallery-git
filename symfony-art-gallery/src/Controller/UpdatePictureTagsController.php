<?php

namespace Sergo\ArtGallery\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sergo\ArtGallery\Service\UpdatePictureTagsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UpdatePictureTagsController extends AbstractController
{

    private UpdatePictureTagsService $pictureTagsService;

    /**
     * @var EntityManagerInterface - соединение с бд
     */
    private EntityManagerInterface $em;

    /**
     * @param LoggerInterface          $logger
     * @param UpdatePictureTagsService $pictureTagsService
     * @param EntityManagerInterface   $em
     */
    public function __construct(
        LoggerInterface $logger,
        UpdatePictureTagsService $pictureTagsService,
        EntityManagerInterface $em
    ) {
        $this->logger = $logger;
        $this->pictureTagsService = $pictureTagsService;
        $this->em = $em;
    }

    public function __invoke(Request $serverRequest): Response
    {
        try {
            $requestData = json_decode($serverRequest->getContent(), true, 512, JSON_THROW_ON_ERROR);
            $attributes = $serverRequest->attributes->all();
            $requestData = array_merge($requestData, $attributes);
            // $validationResult = $this->validateData($requestData);;
            $validationResult = [];
            if (0 === count($validationResult)) {
                $this->em->beginTransaction();
                $responseDto = $this->runService($requestData);
                $this->em->flush();
                $this->em->commit();
                $httpCode = 200;
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
     * @return UpdatePictureTagsService\UpdatedPictureDto
     */
    private function runService(array $requestData): UpdatePictureTagsService\UpdatedPictureDto
    {
        $requestDto = new UpdatePictureTagsService\NewTagsDto(
            $requestData['id'],
            $requestData['tags'],
        );
        return $this->pictureTagsService->addNewTags($requestDto);
    }

    /**
     * формирование полученных данных в нужном формате
     * @param UpdatePictureTagsService\UpdatedPictureDto $responseDto
     * @return array
     */
    private function buildJsonData(UpdatePictureTagsService\UpdatedPictureDto $responseDto): array
    {
        return [
            'id' => $responseDto->getId(),
            'name' => $responseDto->getName(),
            'tags' => $responseDto->getTags(),
        ];
    }
}