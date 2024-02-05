<?php

namespace Sergo\ArtGallery\Controller;

class GetTicketPurchaseReportController extends GetTicketPurchaseReportCollectionController
{
    /**
     * @inheritDoc
     */
    protected function buildHttpCode(array $findTicketReports): int
    {
        return 0 === count($findTicketReports) ? 404 : 200;
    }

    /**
     * @inheritDoc
     */
    protected function buildResult(array $findTicketReports): array
    {
        return 1 === count($findTicketReports) ? $this->serializeTicketReport(current($findTicketReports)) : [
            'status' => 'fail',
            'message' => 'entity not found',
        ];
    }
}
