<?php

namespace Sergo\ArtGallery\Exception;

use Sergo\ArtGallery\Infrastructure\Exception as BaseException;

/**
 * Исключение выбрасываается, если данные с которами работает приложение не валидны
 */
class InvalidDataStructureException extends BaseException\InvalidDataStructureException implements ExceptionInterface
{
}
