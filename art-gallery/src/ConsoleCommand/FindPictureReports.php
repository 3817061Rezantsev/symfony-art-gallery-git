<?php

namespace Sergo\ArtGallery\ConsoleCommand;

use JsonException;
use Sergo\ArtGallery\Infrastructure\Console\CommandInterface;
use Sergo\ArtGallery\Infrastructure\Console\Output\OutputInterface;
use Sergo\ArtGallery\Service\SearchPictureReportsService;

/**
 *  Получение данных об актах о покупке картины
 */
class FindPictureReports implements CommandInterface
{
    /**
     * Отвечает за вывод данных в консоль
     *
     * @var OutputInterface
     */
    private OutputInterface $output;
    /**
     * @var SearchPictureReportsService - поиск данных об актах о покупке картины
     */
    private SearchPictureReportsService $searchPictureReportsService;

    /**
     * Конструктор
     * @param OutputInterface             $output                      - Отвечает за вывод данных в консоль
     * @param SearchPictureReportsService $searchPictureReportsService - поиск данных об актах о покупке картины
     */
    public function __construct(OutputInterface $output, SearchPictureReportsService $searchPictureReportsService)
    {
        $this->output = $output;
        $this->searchPictureReportsService = $searchPictureReportsService;
    }


    /**
     * @inheritDoc
     */
    public static function getShortOptions(): string
    {
        return 'i::';
    }

    /**
     * @inheritDoc
     */
    public static function getLongOptions(): array
    {
        return [
            'id::',
        ];
    }


    /**
     * @inheritDoc
     * @throws JsonException
     */
    public function __invoke(array $params): void
    {
        $dtoCollection = $this->searchPictureReportsService->search(
            (new SearchPictureReportsService\SearchPictureReportsCriteria())->setId($params["id"] ?? null)
                ->setCost($params["cost"] ?? null)
                ->setCurrency($params["currency"] ?? null)
                ->setDateOfPurchase($params["dateOfPurchase"] ?? null)
                ->setVisitorId($params["visitor_id"] ?? null)
                ->setVisitorFullName($params["visitor_fullName"] ?? null)
                ->setVisitorDateOfBirth($params["visitor_dateOfBirth"] ?? null)
                ->setVisitorTelephoneNumber($params["visitor_telephoneNumber"] ?? null)
                ->setPictureId($params["picture_id"] ?? null)
                ->setPictureName($params["picture_name"] ?? null)
                ->setPicturePainterId(isset($params['picture_painter_id']) ? (int)$params['picture_painter_id'] : null)
                ->setPicturePainterFullName($params["picture_painter_fullName"] ?? null)
                ->setPicturePainterDateOfBirth($params["picture_painter_dateOfBirth"] ?? null)
                ->setPicturePainterTelephoneNumber($params["picture_painter_telephoneNumber"] ?? null)
                ->setPictureYear($params["picture_year"] ?? null)
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
        foreach ($dtoCollection as $findPictureRep) {
            $visitorDto = $findPictureRep->getVisitor();
            $visitor = [
                'id'              => $visitorDto->getId(),
                'fullName'        => $visitorDto->getFullName(),
                'dateOfBirth'     => $visitorDto->getDateOfBirth(),
                'telephoneNumber' => $visitorDto->getTelephoneNumber()
            ];
            $painterDto = $findPictureRep->getPicture()->getPainterDto();
            $painter = [
                'id'              => $painterDto->getId(),
                'fullName'        => $painterDto->getFullName(),
                'dateOfBirth'     => $painterDto->getDateOfBirth(),
                'telephoneNumber' => $painterDto->getTelephoneNumber()
            ];
            $pictureDto = $findPictureRep->getPicture();
            $picture = [
                'id'      => $pictureDto->getId(),
                'name'    => $pictureDto->getName(),
                'painter' => $painter,
                'year'    => $pictureDto->getYear(),
            ];
            $jsonData = [
                'id'             => $findPictureRep->getId(),
                'visitor'        => $visitor,
                'picture'        => $picture,
                'dateOfPurchase' => $findPictureRep->getDateOfPurchase(),
                'cost'           => $findPictureRep->getCost(),
                'currency'       => $findPictureRep->getCurrency()
            ];
            $result[] = $jsonData;
        }
        return $result;
    }
}
