<?php

namespace Sergo\ArtGallery\Exception;

use Sergo\ArtGallery\Infrastructure\Exception as BaseException;

/**
 * Выбрасывается исключение если значение не совпадает с набором значений,
 * Обычно это происходит когда функция вызыввает функцию
 * и ожидает значение определённого типа
 */
class UnexpectedValueException extends BaseException\UnexpectedValueException implements ExceptionInterface
{
}
