<?php

namespace Sergo\ArtGallery\Config;

use Sergo\ArtGallery\Infrastructure\Db\SymfonyDi\DiDbExt;
use Sergo\ArtGallery\Infrastructure\Http\SymfonyDi\DiHttpExt;
use Sergo\ArtGallery\Infrastructure\Router\SymfonyDi\DiRouterExt;

/**
 * Коллекция расширений для работы приложения
 */
class ContainerExtensions
{
    /**
     * Коллекция расширений для работы http app
     */
    public static function httpAppContainerExtensions(): array
    {
        return [
            new DiRouterExt(),
            new DiHttpExt(),
            new DiDbExt()
        ];
    }
    /**
     * Коллекция расширений для работы console app
     */
    public static function consoleContainerExtensions(): array
    {
        return [
            new DiRouterExt(),
            new DiHttpExt(),
            new DiDbExt()
        ];
    }
}
