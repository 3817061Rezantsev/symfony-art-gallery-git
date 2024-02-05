<?php

namespace Sergo\ArtGallery\Exception;

use Sergo\ArtGallery\Infrastructure\Exception as BaseException;

/**
 * Исключение кидается в результате ошибок, которые возникают во время выполнения
 */
class RuntimeException extends BaseException\RuntimeException implements ExceptionInterface
{
}
