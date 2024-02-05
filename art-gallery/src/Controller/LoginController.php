<?php

namespace Sergo\ArtGallery\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriFactoryInterface;
use Sergo\ArtGallery\Exception\RuntimeException;
use Sergo\ArtGallery\Infrastructure\Auth\HttpAuthProvider;
use Sergo\ArtGallery\Infrastructure\Controller\ControllerInterface;
use Sergo\ArtGallery\Infrastructure\Http\ServerResponseFactory;
use Sergo\ArtGallery\Infrastructure\ViewTemplate\ViewTemplateInterface;
use Throwable;

/**
 * Контроллер для аутентификации
 */
class LoginController implements ControllerInterface
{
    /**
     * @var ViewTemplateInterface - шаблонизатор
     */
    private ViewTemplateInterface $viewTemplate;
    /**
     * @var HttpAuthProvider - поставщик услуги аутентификации
     */
    private HttpAuthProvider $httpAuthProvider;
    /**
     * @var UriFactoryInterface - фабрика для создания uri
     */
    private UriFactoryInterface $uriFactory;
    /**
     * @var ServerResponseFactory - фабрика для создания http ответа
     */
    private ServerResponseFactory $serverResponseFactory;

    /**
     * @param ViewTemplateInterface $viewTemplate          - шаблонизатор
     * @param HttpAuthProvider      $httpAuthProvider      - поставщик услуги аутентификации
     * @param UriFactoryInterface   $uriFactory            - фабрика для создания uri
     * @param ServerResponseFactory $serverResponseFactory - фабрика для создания http ответа
     */
    public function __construct(
        ViewTemplateInterface $viewTemplate,
        HttpAuthProvider $httpAuthProvider,
        UriFactoryInterface $uriFactory,
        ServerResponseFactory $serverResponseFactory
    ) {
        $this->viewTemplate = $viewTemplate;
        $this->httpAuthProvider = $httpAuthProvider;
        $this->serverResponseFactory = $serverResponseFactory;
        $this->uriFactory = $uriFactory;
    }


    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $serverRequest): ResponseInterface
    {
        try {
            $response = $this->doLogin($serverRequest);
        } catch (Throwable $e) {
            $response = $this->buildErrorResponse($e);
        }
        return $response;
    }

    /**
     * Создает http ответ для ошибки
     * @param Throwable $e
     * @return ResponseInterface
     */
    private function buildErrorResponse(Throwable $e): ResponseInterface
    {
        $context = [
            'errors' => [
                $e->getMessage()
            ]
        ];
        $template = 'errors.twig';
        $httpCode = 500;
        $html = $this->viewTemplate->render(
            $template,
            $context
        );
        return $this->serverResponseFactory->createHtmlResponse($httpCode, $html);
    }

    /**
     * Реализация процесса аутентификации
     * @param ServerRequestInterface $serverRequest
     * @return ResponseInterface
     */
    private function doLogin(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $response = null;
        $context = [];
        if ('POST' === $serverRequest->getMethod()) {
            $authData = [];
            parse_str($serverRequest->getBody(), $authData);
            $this->validateAuthData($authData);
            if ($this->isAuth($authData['login'], $authData['password'])) {
                $queryParams = $serverRequest->getQueryParams();
                $redirect = array_key_exists('redirect', $queryParams) ?
                    $this->uriFactory->createUri($queryParams['redirect']) :
                    $this->uriFactory->createUri('/');
                $response = $this->serverResponseFactory->redirect($redirect);
            } else {
                $context['errMsg'] = 'Wrong login or password';
            }
        }
        if (null === $response) {
            $html = $this->viewTemplate->render('login.twig', $context);
            $response = $this->serverResponseFactory->createHtmlResponse(200, $html);
        }
        return $response;
    }

    /**
     * Валидация данных
     * @param array $authData
     * @return void
     */
    private function validateAuthData(array $authData): void
    {
        if (false === array_key_exists('login', $authData)) {
            throw new RuntimeException('No login');
        }
        if (false === array_key_exists('password', $authData)) {
            throw new RuntimeException('No password');
        }
        if (false === is_string($authData['login'])) {
            throw new RuntimeException('No string login');
        }
        if (false === is_string($authData['password'])) {
            throw new RuntimeException('No string password');
        }
    }

    /**
     * Проверка на существование пользователя в системе
     * @param string $login
     * @param string $password
     * @return bool
     */
    private function isAuth(string $login, string $password): bool
    {
        return $this->httpAuthProvider->auth($login, $password);
    }
}
