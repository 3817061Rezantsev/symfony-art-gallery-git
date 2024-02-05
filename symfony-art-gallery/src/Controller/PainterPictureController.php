<?php

namespace Sergo\ArtGallery\Controller;

use Sergo\ArtGallery\Service\SearchPicturesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PainterPictureController extends AbstractController
{
    private SearchPicturesService $picturesService;

    /**
     * @param SearchPicturesService $picturesService
     */
    public function __construct(
        SearchPicturesService $picturesService
    ) {
        $this->picturesService = $picturesService;
    }

    public function __invoke(Request $serverRequest): Response
    {
        $params = $serverRequest->attributes->all();
        $pictures = $this->picturesService->search(
            (new SearchPicturesService\SearchPicturesCriteria())
                ->setPainterId(isset($params['id']) ? (int)$params['id'] : null)
        );
        $count = count($pictures);
        $template = 'painter.picture.twig';
        $context = [
            'pictures' => $pictures,
            'count'    => $count
        ];
        return $this->renderForm(
            $template,
            $context
        );
    }


}