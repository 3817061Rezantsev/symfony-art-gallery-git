<?php

namespace Sergo\ArtGallery\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use Sergo\ArtGallery\Exception;

/**
 * Валюта
 * @ORM\Entity()
 * @ORM\Table(
 *     name="currency",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(name="currency_code_unq", columns={"code"}),
 *          @ORM\UniqueConstraint(name="currency_name_unq", columns={"name"})
 *     }
 * )
 */
class Currency
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="currency_id_seq")
     */
    private ?int $id = null;

    /**
     * код валюты
     * @ORM\Column(name="code", type="string", length=3, nullable=false)
     * @var string
     */
    private string $code;
    /**
     * имя валюты
     * @ORM\Column(name="name", type="string", length=3, nullable=false)
     * @var string
     */
    private string $name;
    /**
     * описание валюты
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     * @var string
     */
    private string $description;

    /**
     * (RUB)
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $code        - код валюты
     * @param string $name        - имя валюты
     * @param string $description - описание валюты
     */
    public function __construct(string $code, string $name, string $description)
    {
        $this->validate($code, $name, $description);
        $this->code = $code;
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * валидация данных о валюте
     * @param string $code
     * @param string $name
     * @param string $description
     * @return void
     */
    private function validate(string $code, string $name, string $description): void
    {
        if (1 !== preg_match('/^\d{3}$/', $code)) {
            throw new Exception\DomainException("некорректный формат валюты");
        }
        if (1 !== preg_match('/^[A-Z]{3}$/', $name)) {
            throw new Exception\DomainException("некорректный формат валюты");
        }
        if (strlen($description) > 255) {
            throw new Exception\DomainException('Currency description length cannot be more then 255 symbols');
        }
        if (trim($description) === '') {
            throw new Exception\DomainException('Currency description cannot be empty');
        }
    }


    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    public function __toString()
    {
        return $this->name;
    }
}
