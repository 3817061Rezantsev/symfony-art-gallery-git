<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ServerRequestInterface;
use Sergo\ArtGallery\Config\AppConfig;
use Sergo\ArtGallery\Config\ContainerExtensions;
use Sergo\ArtGallery\Infrastructure\DI\ContainerInterface;
use Sergo\ArtGallery\Infrastructure\DI\SymfonyDiContainerInit;
use Sergo\ArtGallery\Infrastructure\HttpApplication\App;
use Psr\Log\LoggerInterface;
use Sergo\ArtGallery\Infrastructure\Router\RouterInterface;
use Sergo\ArtGallery\Infrastructure\View\RenderInterface;

error_reporting(E_ALL & ~E_DEPRECATED);
$httpResponse = (new App(
    static function (ContainerInterface $di): RouterInterface {
        return $di->get(RouterInterface::class);
    },
    static function (ContainerInterface $di): LoggerInterface {
        return $di->get(LoggerInterface::class);
    },
    static function (ContainerInterface $di): AppConfig {
        return $di->get(AppConfig::class);
    },
    static function (ContainerInterface $di): RenderInterface {
        return $di->get(RenderInterface::class);
    },
    new SymfonyDiContainerInit(
        new SymfonyDiContainerInit\ContainerParams(
            __DIR__ . '/../config/dev/di.xml',
            ['kernel.project_dir' => __DIR__ . '/../'],
            ContainerExtensions::httpAppContainerExtensions()
        ),
        new SymfonyDiContainerInit\CacheParams(
            'DEV' !== getenv('ENV_TYPE'),
            __DIR__ . '/../var/cache/di-symfony/CachedContainer.php'
        )
    )
))->dispatch(
    (static function (): ServerRequestInterface {
        $psr17Factory = new Psr17Factory();
        $creator = new ServerRequestCreator(
            $psr17Factory, // ServerRequestFactory
            $psr17Factory, // UriFactory
            $psr17Factory, // UploadedFileFactory
            $psr17Factory  // StreamFactory
        );
        return $creator->fromGlobals();
    })()
);
