<?php

namespace Sergo\ArtGallery\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Пользователь приложения
 * @ORM\Entity()
 * @ORM\Table(
 *     name="users",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="users_login_unq", columns={"login"})
 *     },
 *     indexes={
 *          @ORM\Index(name="users_full_name_idx", columns={"full_name"}),
 *          @ORM\Index(name="users_telephone_number_idx", columns={"telephone_number"})
 *     }
 * )
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="role", type="string", length=60)
 * @ORM\DiscriminatorMap(
 *     {
 *     "admin" = \Sergo\ArtGallery\Entity\Admin::class,
 *     "visitor" = \Sergo\ArtGallery\Entity\Visitor::class,
 *     }
 * )
 */
abstract class AbstractUser
{
    /**
     * ID пользователя
     * @var int
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     */
    protected int $id;

    /**
     * Имя пользователя
     * @ORM\Column(name="full_name", type="string", length=255, nullable=false)
     * @var string
     */
    protected string $fullName;

    /**
     * Дата рождения пользователя
     * @ORM\Column(name="date_of_birth", type="date_immutable", nullable=false)
     * @var DateTimeImmutable
     */
    protected DateTimeImmutable $dateOfBirth;

    /**
     * Номер телефона пользователя
     * @ORM\Column(name="telephone_number", type="string", length=30, nullable=false)
     * @var string
     */
    protected string $telephoneNumber;

    /*-----------------------------------------------------------------*/
    /**
     * @param int               $id              - ID пользователя
     * @param string            $fullName        - Имя пользователя
     * @param DateTimeImmutable $dateOfBirth     - Дата рождения пользователя
     * @param string            $telephoneNumber - Номер телефона пользователя
     */
    public function __construct(int $id, string $fullName, DateTimeImmutable $dateOfBirth, string $telephoneNumber)
    {
        $this->id = $id;
        $this->fullName = $fullName;
        $this->dateOfBirth = $dateOfBirth;
        $this->telephoneNumber = $telephoneNumber;
    }

    /**
     * Получить ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Получить Имя
     *
     * @return string
     */
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * Получить дату рождения
     *
     * @return DateTimeImmutable
     */
    public function getDateOfBirth(): DateTimeImmutable
    {
        return $this->dateOfBirth;
    }

    /**
     * Получить номер телефона
     *
     * @return string
     */
    public function getTelephoneNumber(): string
    {
        return $this->telephoneNumber;
    }
}
