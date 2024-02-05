<?php

namespace Sergo\ArtGallery\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sergo\ArtGallery\Exception\RuntimeException;
use Sergo\ArtGallery\Infrastructure\Auth\HttpAuthProvider;
use Sergo\ArtGallery\Infrastructure\Controller\ControllerInterface;
use Sergo\ArtGallery\Infrastructure\Db\ConnectionInterface;
use Sergo\ArtGallery\Infrastructure\Http\ServerResponseFactory;
use Psr\Log\LoggerInterface;
use Sergo\ArtGallery\Infrastructure\ViewTemplate\ViewTemplateInterface;
use Sergo\ArtGallery\Service\ArrivalNewPictureService;
use Sergo\ArtGallery\Service\SearchPaintersService;
use Sergo\ArtGallery\Service\SearchPicturesService;
use Throwable;

class PictureAdministrationController implements ControllerInterface
{
    /**
     * Поставщик авторизации
     * @var HttpAuthProvider
     */
    private HttpAuthProvider $httpAuthProvider;

    /**
     * @var LoggerInterface - логгер
     */
    private LoggerInterface $logger;

    /**
     * @var SearchPicturesService - сервис поиска картин
     */
    private SearchPicturesService $searchPicturesService;

    /**
     * @var SearchPaintersService - сервис поиска художников
     */
    private SearchPaintersService $searchPaintersService;

    /**
     * @var ViewTemplateInterface - Шаблонизатор для рендера HTML
     */
    private ViewTemplateInterface $viewTemplate;

    /**
     * @var ArrivalNewPictureService - сервис добавления новой картины
     */
    private ArrivalNewPictureService $arrivalNewPictureService;
    /**
     * @var ServerResponseFactory - фабрика создания http ответа
     */
    private ServerResponseFactory $serverResponseFactory;
    /**
     * @var EntityManagerInterface - соединение с бд
     */
    private EntityManagerInterface $em;

    /**
     * @param LoggerInterface          $logger                   - логгер
     * @param SearchPicturesService    $searchPicturesService    - сервис поиска картин
     * @param ViewTemplateInterface    $viewTemplate             - Шаблонизатор для рендера HTML
     * @param ArrivalNewPictureService $arrivalNewPictureService - сервис добавления новой картины
     * @param SearchPaintersService    $paintersService          - сервис поиска художников
     * @param HttpAuthProvider         $httpAuthProvider         - Поставщик авторизации
     * @param ServerResponseFactory    $serverResponseFactory    - фабрика создания http ответа
     * @param EntityManagerInterface   $em                       - соединение с бд
     */
    public function __construct(
        LoggerInterface $logger,
        SearchPicturesService $searchPicturesService,
        ViewTemplateInterface $viewTemplate,
        ArrivalNewPictureService $arrivalNewPictureService,
        SearchPaintersService $paintersService,
        HttpAuthProvider $httpAuthProvider,
        ServerResponseFactory $serverResponseFactory,
        EntityManagerInterface $em
    ) {
        $this->logger = $logger;
        $this->searchPicturesService = $searchPicturesService;
        $this->viewTemplate = $viewTemplate;
        $this->arrivalNewPictureService = $arrivalNewPictureService;
        $this->searchPaintersService = $paintersService;
        $this->httpAuthProvider = $httpAuthProvider;
        $this->serverResponseFactory = $serverResponseFactory;
        $this->em = $em;
    }


    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        try {
            if (false === $this->httpAuthProvider->isAuth()) {
                return $this->httpAuthProvider->doAuth($serverRequest->getUri());
            }

            $this->logger->info('run PictureAdministrationController::__invoke');
            $resultCreatingEntities = [];
            if ('POST' === $serverRequest->getMethod()) {
                $resultCreatingEntities = $this->creationOfPicture($serverRequest);
            }

            $dtoCollection = $this->searchPicturesService->search(new SearchPicturesService\SearchPicturesCriteria());
            $dtoPainters = $this->searchPaintersService->search(new SearchPaintersService\SearchPainterCriteria());
            $viewData = [
                'pictures' => $dtoCollection,
                'painters' => $dtoPainters
            ];
            $context = array_merge($viewData, $resultCreatingEntities);
            $template = 'picture.administration.twig';
            $httpCode = 200;
        } catch (Throwable $e) {
            $httpCode = 500;
            $context = [
                'errors' => [
                    $e->getMessage()
                ],
                'code' => $httpCode,

            ];
            $template = 'errors.twig';
        }
        $html = $this->viewTemplate->render(
            $template,
            $context
        );
        return $this->serverResponseFactory->createHtmlResponse($httpCode, $html);
    }

    /**
     * Создание картины
     * @param ServerRequestInterface $serverRequest
     * @return array
     */
    private function creationOfPicture(ServerRequestInterface $serverRequest): array
    {
        $dataToCreate = [];
        parse_str($serverRequest->getBody(), $dataToCreate);
        $result = [];
        $result['formValidationResults'] = $this->validateData($dataToCreate);
        if (0 === count($result['formValidationResults'])) {
            $this->create($dataToCreate);
        } else {
            $result['pictureData'] = $dataToCreate;
        }

        return $result;
    }

    /**
     * Валидация данных
     * @param $dataToCreate
     * @return array
     */
    private function validateData($dataToCreate): array
    {
        $err = [];
        if (false === is_array($dataToCreate)) {
            $err[] = 'Объект должен быть массивом';
        } else {
            if (false === array_key_exists('name', $dataToCreate)) {
                $err['name'] = 'Нет имени картины';
            } elseif (false === is_string($dataToCreate['name'])) {
                $err['name'] = 'Имя - не строка';
            } elseif ('' === trim($dataToCreate['name'])) {
                $err['name'] = 'Имя - пустая строка';
            }

            if (false === array_key_exists('year', $dataToCreate)) {
                $err['year'] = 'Нет года написания картины';
            } elseif (false === is_string($dataToCreate['year'])) {
                $err['year'] = 'Год должен быть строкой';
            } elseif ('' === trim($dataToCreate['year'])) {
                $err['year'] = 'Год не должен быть пустым';
            }

            if (false === array_key_exists('painter_id', $dataToCreate)) {
                $err['painter_id'] = 'Нет автора';
            }
        }
        return $err;
    }

    /**
     * Создание картины в бд
     * @param array $dataToCreate
     * @return void
     */
    private function create(array $dataToCreate): void
    {
        try {
            $this->em->beginTransaction();
            $this->arrivalNewPictureService->registerPicture(
                new ArrivalNewPictureService\NewPictureDto(
                    $dataToCreate['name'],
                    (int)$dataToCreate['painter_id'],
                    $dataToCreate['year'],
                )
            );
            $this->em->flush();
            $this->em->commit();
        } catch (Throwable $exception) {
            $this->em->rollBack();
            throw new RuntimeException(
                'Picture creation error' . $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }
}
