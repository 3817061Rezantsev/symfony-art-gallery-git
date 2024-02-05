<?php

namespace Sergo\ArtGallery\ConsoleCommand;

use Sergo\ArtGallery\Service\SearchPictureReportsService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FindPicturePurchaseReports extends Command
{
    /**
     * @var SearchPictureReportsService - поиск данных об актах о покупке картины
     */
    private SearchPictureReportsService $searchPictureReportsService;

    public function __construct(SearchPictureReportsService $searchPictureReportsService)
    {
        $this->searchPictureReportsService = $searchPictureReportsService;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('art-gallery:find-picture-purchase-report');
        $this->setDescription('Found picture-purchase-report');
        $this->setHelp('Find picture-purchase-report by criteria');
        $this->addOption('visitor_fullName', 's', InputOption::VALUE_REQUIRED, 'Found visitor full name');
        $this->addOption('id', 'i', InputOption::VALUE_REQUIRED, 'Found Id');
        $this->addOption('picture_name', 'N', InputOption::VALUE_REQUIRED, 'Found picture name');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $params = $input->getOptions();
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
        $output->writeln(json_encode($jsonData, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return self::SUCCESS;
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