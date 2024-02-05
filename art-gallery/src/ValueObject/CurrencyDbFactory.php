<?php

namespace Sergo\ArtGallery\ValueObject;

use Sergo\ArtGallery\Entity\CurrencyFactoryInterface;
use Sergo\ArtGallery\Exception\RuntimeException;
use Sergo\ArtGallery\Infrastructure\Db\ConnectionInterface;

/**
 * имплементация создания объекта валюты
 */
class CurrencyDbFactory implements CurrencyFactoryInterface
{
    /**
     * критерй поиска
     */
    private const SEARCH_CRITERIA_TO_SQL_PARTS = [
        'currency' => 'c.name'
    ];

    /**
     * запрос в бд
     */
    private const BASE_SQL_SEARCH = <<<EOF
SELECT
    c.code               as code,
    c.name               as name,
    c.description        as description
FROM currency as c
EOF;

    /**
     * @var ConnectionInterface - соединение с бд
     */
    private ConnectionInterface $connection;

    /**
     * @param ConnectionInterface $connection - соединение с бд
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Загрузка данных
     * @param array $criteria
     * @return array
     */
    private function loadData(array $criteria): array
    {
        $sql = self::BASE_SQL_SEARCH;
        $params = [];
        $whereParts = [];
        $unsupportedSearchCriteria = [];
        foreach ($criteria as $criteriaName => $criteriaValue) {
            if (array_key_exists($criteriaName, self::SEARCH_CRITERIA_TO_SQL_PARTS)) {
                $sqlParts = self::SEARCH_CRITERIA_TO_SQL_PARTS[$criteriaName];
                $whereParts[] = "$sqlParts=:$criteriaName";
                $params[$criteriaName] = $criteriaValue;
            } else {
                $unsupportedSearchCriteria[] = $criteriaName;
            }
        }
        if (count($unsupportedSearchCriteria) > 0) {
            $errMsg = 'Unsupported Search Criteria for text doc ' . implode(', ', $unsupportedSearchCriteria);
            throw new RuntimeException($errMsg);
        }
        if (count($whereParts) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $whereParts);
        }
        $statement = $this->connection->prepare($sql);
        if (false === $statement->execute($params)) {
            throw new RuntimeException("Execute sql query exception");
        }
        return $statement->fetchAll();
    }

    /**
     * Поиск валюты по критериям
     * @param array $criteria
     * @return Currency
     */
    public function findBy(array $criteria): Currency
    {
        $data = current($this->loadData($criteria));
        return new Currency(
            $data['code'],
            $data['name'],
            $data['description'],
        );
    }

    /**
     * @inheritDoc
     */
    public function findByName(string $name): Currency
    {
        return $this->findBy(['currency' => $name]);
    }
}
