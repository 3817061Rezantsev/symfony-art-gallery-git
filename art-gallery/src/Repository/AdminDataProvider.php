<?php

namespace Sergo\ArtGallery\Repository;

use Sergo\ArtGallery\Entity\Admin;
use Sergo\ArtGallery\Infrastructure\Auth\AdminDataProviderInterface;

/**
 * поставщик данных о логине/пароле пользователя
 */
class AdminDataProvider implements AdminDataProviderInterface
{
    /**
     * @var Admin создаем обертку над сущностью
     */
    private Admin $admin;

    /**
     * @param Admin $admin
     */
    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }


    /**
     * @inheritDoc
     */
    public function getLogin(): string
    {
        return $this->admin->getLogin();
    }

    /**
     * @inheritDoc
     */
    public function getPassword(): string
    {
        return $this->admin->getPassword();
    }
}
