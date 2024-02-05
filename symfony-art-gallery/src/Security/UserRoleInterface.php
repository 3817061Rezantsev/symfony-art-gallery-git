<?php

namespace Sergo\ArtGallery\Security;

/**
 * роли пользователя
 */
interface UserRoleInterface
{
    /**
     * аутентифицированный пользователь
     */
    public const ROLE_AUTH_USER = 'ROLE_AUTH_USER';
}