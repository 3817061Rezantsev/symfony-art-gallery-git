<?php

namespace Sergo\ArtGallery\Exception;

use Sergo\ArtGallery\Infrastructure\Exception as BaseException;

/**
 * Выбрасывает исключение, если значеине не соответствует определенной допустимой области данных
 */
class DomainException extends BaseException\DomainException implements ExceptionInterface
{
}
