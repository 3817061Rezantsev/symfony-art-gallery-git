<?php

namespace Sergo\ArtGallery\Exception;

/**
 * Выбрасывает исключение, если значеине не соответствует определенной допустимой области данных
 */
class DomainException extends \DomainException implements ExceptionInterface
{
}
