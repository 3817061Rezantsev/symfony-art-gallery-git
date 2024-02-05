<?php

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Sergo\ArtGallery\Config\ContainerExtensions;
use Sergo\ArtGallery\Infrastructure\DI\SymfonyDiContainerInit;

require_once __DIR__ . '/../vendor/autoload.php';
$container = (new SymfonyDiContainerInit(
    new SymfonyDiContainerInit\ContainerParams(
        __DIR__ . '/../config/dev/di.xml',
        ['kernel.project_dir' => __DIR__ . '/../'],
        ContainerExtensions::httpAppContainerExtensions()
    ),
    new SymfonyDiContainerInit\CacheParams(
        'DEV' === getenv('ENV_TYPE'),
        __DIR__ . '/../var/cache/di-symfony/CachedContainer.php'
    )
))();
$entityManager = $container->get(EntityManagerInterface::class);

return ConsoleRunner::createHelperSet($entityManager);
