<?php

namespace Sergo\ArtGallery\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sergo\ArtGallery\Exception\RuntimeException;
use Sergo\ArtGallery\Form\CreateTicketReportForm;
use Sergo\ArtGallery\Service\ArrivalNewTicketReportService;
use Sergo\ArtGallery\Service\SearchTicketReportsService;
use Sergo\ArtGallery\Service\SearchTicketReportsService\SearchTicketReportCriteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TicketReportAdministrationController extends AbstractController
{
    /**
     * @var SearchTicketReportsService - сервис поиска актов о покупке билетов
     */
    private SearchTicketReportsService $searchTicketReportsService;

    /**
     * @var ArrivalNewTicketReportService - прибытие нового билета
     */
    private ArrivalNewTicketReportService $arrivalNewTicketReportService;

    /**
     * @var EntityManagerInterface - соединение с бд
     */
    private EntityManagerInterface $em;

    /**
     * @param SearchTicketReportsService    $searchTicketReportsService    - сервис поиска актов о покупке билетов
     * @param ArrivalNewTicketReportService $arrivalNewTicketReportService - прибытие нового билета
     * @param EntityManagerInterface        $em                            - соединение с бд
     */
    public function __construct(
        SearchTicketReportsService $searchTicketReportsService,
        ArrivalNewTicketReportService $arrivalNewTicketReportService,
        EntityManagerInterface $em
    ) {
        $this->searchTicketReportsService = $searchTicketReportsService;
        $this->arrivalNewTicketReportService = $arrivalNewTicketReportService;
        $this->em = $em;
    }

    public function __invoke(Request $serverRequest): Response
    {
        $form = $this->createForm(CreateTicketReportForm::class);
        $form->handleRequest($serverRequest);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->create($data);
            $form = $this->createForm(CreateTicketReportForm::class);
        }
        $template = 'ticketReport.administration.twig';
        $dtoTicketReports = $this->searchTicketReportsService->search(new SearchTicketReportCriteria());
        $context = [
            'form_ticket_report' => $form,
            'ticketReports'      => $dtoTicketReports
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
            $this->arrivalNewTicketReportService->registerTicketReport(
                new ArrivalNewTicketReportService\NewTicketReportDto(
                    $requestData['visitor_id'],
                    $requestData['ticket_id'],
                    $requestData['dateOfPurchase'],
                )
            );
            $this->em->flush();
            $this->em->commit();
        } catch (Throwable $exception) {
            $this->em->rollBack();
            throw new RuntimeException(
                'Picture creation error' . $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }
}
