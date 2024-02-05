<?php

namespace Sergo\ArtGallery\Controller;

/**
 * Контроллер для просмотра данных о картине
 */
class GetPictureController extends GetPictureCollectionController
{
    /**
     * @inheritDoc
     */
    protected function buildHttpCode(array $findPictures): int
    {
        return 0 === count($findPictures) ? 404 : 200;
    }

    /**
     * @inheritDoc
     */
    protected function buildResult(array $findPictures): array
    {
        return 1 === count($findPictures) ? $this->serializePicture(current($findPictures)) : [
            'status' => 'fail',
            'message' => 'entity not found',
        ];
    }
}
