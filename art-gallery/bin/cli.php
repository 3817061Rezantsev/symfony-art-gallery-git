#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Sergo\ArtGallery\Config\ContainerExtensions;
use Sergo\ArtGallery\Infrastructure\Console\AppConsole;
use Sergo\ArtGallery\Infrastructure\Console\Output\OutputInterface;
use Sergo\ArtGallery\Infrastructure\DI\ContainerInterface;
use Sergo\ArtGallery\Infrastructure\DI\SymfonyDiContainerInit;

error_reporting(E_ALL & ~E_DEPRECATED);
(new AppConsole(
    require __DIR__ . '/../config/console.handlers.php',
    static function (ContainerInterface $di): OutputInterface {
        return $di->get(OutputInterface::class);
    },
    new SymfonyDiContainerInit(
        new SymfonyDiContainerInit\ContainerParams(
            __DIR__ . '/../config/dev/di.xml',
            ['kernel.project_dir' => __DIR__ . '/../'],
            ContainerExtensions::consoleContainerExtensions()
        ),
        new SymfonyDiContainerInit\CacheParams(
            'DEV' !== getenv('ENV_TYPE'),
            __DIR__ . '/../var/cache/di-symfony/CachedContainer.php'
        )
    )
))->dispatch();
