<?php

namespace Sergo\ArtGallery\ConsoleCommand;

use Sergo\ArtGallery\Service\SearchPicturesService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FindPictures extends Command
{
    /**
     * @var SearchPicturesService поиск данных о покупке картин
     */
    private SearchPicturesService $searchPicturesService;

    /**
     * Конструктор
     * @param SearchPicturesService $searchPicturesService - поиск данных о покупке картин
     */
    public function __construct(SearchPicturesService $searchPicturesService)
    {
        $this->searchPicturesService = $searchPicturesService;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('art-gallery:find-pictures');
        $this->setDescription('Found pictures');
        $this->setHelp('Find pictures by criteria');
        $this->addOption('painter_fullName', 's', InputOption::VALUE_REQUIRED, 'Found painter full name');
        $this->addOption('id', 'i', InputOption::VALUE_REQUIRED, 'Found Id');
        $this->addOption('name', 'N', InputOption::VALUE_REQUIRED, 'Found name');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $params = $input->getOptions();
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
        $table = new Table($output);
        $table->setHeaders(
            [
                'id',
                'painter_id',
                'painter_fullName',
                'painter_dateOfBirth',
                'painter_telephoneNumber',
                'name',
                'year'
            ]
        );

        $jsonData = $this->buildJsonData($dtoCollection);
        $table->setRows($jsonData);
        $table->render();
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
        foreach ($dtoCollection as $pictureDto) {
            $painterDto = $pictureDto->getPainterDto();
            $result[] = [
                'id'                      => $pictureDto->getId(),
                'painter_id'              => $painterDto->getId(),
                'painter_fullName'        => $painterDto->getFullName(),
                'painter_dateOfBirth'     => $painterDto->getDateOfBirth(),
                'painter_telephoneNumber' => $painterDto->getTelephoneNumber(),
                'name'                    => $pictureDto->getName(),
                'year'                    => $pictureDto->getYear(),
            ];
        }
        return $result;
    }
}
