<?php

namespace Sergo\ArtGallery\Exception;

use Sergo\ArtGallery\Infrastructure\Exception as BaseException;

/**
 * Не удалось создать конфиг приложения
 */
class ErrorCreateAppConfigException extends BaseException\ErrorCreateAppConfigException implements ExceptionInterface
{
}
