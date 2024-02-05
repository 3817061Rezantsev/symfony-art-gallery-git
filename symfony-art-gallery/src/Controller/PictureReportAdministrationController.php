<?php

namespace Sergo\ArtGallery\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sergo\ArtGallery\Exception\RuntimeException;
use Sergo\ArtGallery\Form\CreatePictureReportForm;
use Sergo\ArtGallery\Service\ArrivalNewPictureReportService;
use Sergo\ArtGallery\Service\SearchPictureReportsService;
use Sergo\ArtGallery\Service\SearchPictureReportsService\SearchPictureReportsCriteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PictureReportAdministrationController extends AbstractController
{
    /**
     * @var SearchPictureReportsService - сервис поиска актов о покупке картин
     */
    private SearchPictureReportsService $searchPictureReportsService;

    /**
     * @var ArrivalNewPictureReportService - сервис добавления новых картин
     */
    private ArrivalNewPictureReportService $arrivalNewPictureReportService;

    /**
     * @var EntityManagerInterface - соединение с бд
     */
    private EntityManagerInterface $em;

    /**
     * @param SearchPictureReportsService    $searchPictureReportsService    - сервис поиска актов о покупке картин
     * @param ArrivalNewPictureReportService $arrivalNewPictureReportService - сервис добавления новых картин
     * @param EntityManagerInterface         $em                             - соединение с бд
     */
    public function __construct(
        SearchPictureReportsService $searchPictureReportsService,
        ArrivalNewPictureReportService $arrivalNewPictureReportService,
        EntityManagerInterface $em
    ) {
        $this->searchPictureReportsService = $searchPictureReportsService;
        $this->arrivalNewPictureReportService = $arrivalNewPictureReportService;
        $this->em = $em;
    }

    public function __invoke(Request $serverRequest): Response
    {
        $form = $this->createForm(CreatePictureReportForm::class);
        $form->handleRequest($serverRequest);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->create($data);
            $form = $this->createForm(CreatePictureReportForm::class);
        }
        $template = 'pictureReport.administration.twig';
        $dtoPictureReports = $this->searchPictureReportsService->search(new SearchPictureReportsCriteria());
        $context = [
            'form_picture_report' => $form,
            'pictureReports'      => $dtoPictureReports
        ];
        return $this->renderForm(
            $template,
            $context
        );
    }

    /**
     * Добавление сущности в бд
     * @param array $requestData
     * @return void
     */
    private function create(array $requestData): void
    {
        try {
            $this->em->beginTransaction();
            $this->arrivalNewPictureReportService->registerPictureReport(
                new ArrivalNewPictureReportService\NewPictureReportDto(
                    $requestData['visitor_id'],
                    $requestData['picture_id'],
                    $requestData['dateOfPurchase'],
                    $requestData['cost'],
                    $requestData['currency'],
                )
            );
            $this->em->flush();
            $this->em->commit();
        } catch (Throwable $exception) {
            $this->em->rollBack();
            throw new RuntimeException(
                'Picture purchase report creation error' . $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }
}
