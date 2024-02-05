<?php

namespace Sergo\ArtGallery\Controller;

/**
 * Контроллер для получения данных об акте о покупке картины
 */
class GetPicturePurchaseReportController extends GetPicturePurchaseReportCollectionController
{
    /**
     * @inheritDoc
     */
    protected function buildHttpCode(array $findPictureReport): int
    {
        return 0 === count($findPictureReport) ? 404 : 200;
    }

    /**
     * @inheritDoc
     */
    protected function buildResult(array $findPictureReport): array
    {
        return 1 === count($findPictureReport) ? $this->serializePictureReport(current($findPictureReport)) : [
            'status'  => 'fail',
            'message' => 'entity not found',
        ];
    }
}
