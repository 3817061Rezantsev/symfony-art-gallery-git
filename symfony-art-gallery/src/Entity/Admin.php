<?php

namespace Sergo\ArtGallery\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Sergo\ArtGallery\Exception;

/**
 * Администратор приложения
 * @ORM\Entity(repositoryClass=\Sergo\ArtGallery\Repository\AdminDoctrineRepository::class)
 */
class Admin extends AbstractUser
{
    /**
     * @var string - Логин
     * @ORM\Column(name="login", type="string", length=50, nullable=false)
     */
    private string $login;

    /**
     * @ORM\Column(name="password", type="string", length=60, nullable=false)
     * @var string - Пароль
     */
    private string $password;

    /**
     * @param int               $id              - ID пользователя
     * @param string            $fullName        - Имя пользователя
     * @param DateTimeImmutable $dateOfBirth     - Дата рождения пользователя
     * @param string            $telephoneNumber - Номер телефона пользователя
     * @param string            $login           - Логин
     * @param string            $password        - Пароль
     */
    public function __construct(
        int $id,
        string $fullName,
        DateTimeImmutable $dateOfBirth,
        string $telephoneNumber,
        string $login,
        string $password
    ) {
        parent::__construct($id, $fullName, $dateOfBirth, $telephoneNumber);
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * возвращает логин
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * возвращает пароль
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
