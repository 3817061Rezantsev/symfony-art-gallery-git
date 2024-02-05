<?php

namespace Sergo\ArtGallery\ConsoleCommand;

use JsonException;
use Sergo\ArtGallery\Infrastructure\Console\CommandInterface;
use Sergo\ArtGallery\Infrastructure\Console\Output\OutputInterface;
use Sergo\ArtGallery\Service\SearchPicturesService;

/**
 *  Получение данных о картинах
 */
class FindPictures implements CommandInterface
{
    /**
     * Отвечает за вывод данных в консоль
     *
     * @var OutputInterface
     */
    private OutputInterface $output;
    /**
     * @var SearchPicturesService поиск данных о покупке картин
     */
    private SearchPicturesService $searchPicturesService;

    /**
     * Конструктор
     * @param OutputInterface       $output                - Отвечает за вывод данных в консоль
     * @param SearchPicturesService $searchPicturesService - поиск данных о покупке картин
     */
    public function __construct(OutputInterface $output, SearchPicturesService $searchPicturesService)
    {
        $this->output = $output;
        $this->searchPicturesService = $searchPicturesService;
    }

    /**
     * @inheritDoc
     */
    public static function getShortOptions(): string
    {
        return 'n:a:';
    }

    /**
     * @inheritDoc
     */
    public static function getLongOptions(): array
    {
        return [
            'name:',
            'id::',
        ];
    }

    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function __invoke(array $params): void
    {
        $dtoCollection = $this->searchPicturesService->search(
            (new SearchPicturesService\SearchPicturesCriteria())
                ->setId($params['id'] ?? null)
                ->setName($params['name'] ?? null)
                ->setPainterId(isset($params['painter_id']) ? (int)$params['painter_id'] : null)
                ->setPainterFullName($params['painter_fullName'] ?? null)
                ->setPainterDateOfBirth($params['painter_dateOfBirth'] ?? null)
                ->setPainterTelephoneNumber($params['painter_telephoneNumber'] ?? null)
                ->setYear($params['year'] ?? null)
                ->setStart($params['start'] ?? null)
                ->setEnd($params['end'] ?? null)
        );
        $jsonData = $this->buildJsonData($dtoCollection);
        $this->output->print(json_encode($jsonData, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * формирование полученных данных в нужном формате
     * @param array $dtoCollection
     * @return array
     */
    private function buildJsonData(array $dtoCollection): array
    {
        $result = [];
        foreach ($dtoCollection as $pictureDto) {
            $painterDto = $pictureDto->getPainterDto();
            $painter = [
                'id'              => $painterDto->getId(),
                'fullName'        => $painterDto->getFullName(),
                'dateOfBirth'     => $painterDto->getDateOfBirth(),
                'telephoneNumber' => $painterDto->getTelephoneNumber()
            ];
            $result[] = [
                'id'      => $pictureDto->getId(),
                'name'    => $pictureDto->getName(),
                'painter' => $painter,
                'year'    => $pictureDto->getYear(),
            ];
        }
        return $result;
    }
}
