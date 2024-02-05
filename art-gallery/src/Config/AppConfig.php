<?php

namespace Sergo\ArtGallery\Config;

use Sergo\ArtGallery\Infrastructure\HttpApplication\AppConfig as BaseConfig;

/**
 * Класс ООП обертка или конфиг приложения
 */
class AppConfig extends BaseConfig
{
    /**
     * Uri форма аутентификации
     * @var string $loginUri
     */
    protected string $loginUri;

    /**
     * Возвращает Uri форму аутентификации
     * @return mixed
     */
    public function getLoginUri(): string
    {
        return $this->loginUri;
    }

    /**
     * Устанавливает Uri форму аутентификации
     * @param mixed $loginUri
     * @return AppConfig
     */
    public function setLoginUri(string $loginUri): AppConfig
    {
        $this->loginUri = $loginUri;
        return $this;
    }
}
