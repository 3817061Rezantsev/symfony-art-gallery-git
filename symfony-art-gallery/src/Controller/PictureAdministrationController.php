<?php

namespace Sergo\ArtGallery\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sergo\ArtGallery\Exception\RuntimeException;
use Sergo\ArtGallery\Form\CreatePictureForm;
use Sergo\ArtGallery\Service\ArrivalNewPictureService;
use Sergo\ArtGallery\Service\SearchPicturesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PictureAdministrationController extends AbstractController
{

    /**
     * @var SearchPicturesService - сервис поиска картин
     */
    private SearchPicturesService $searchPicturesService;

    /**
     * @var ArrivalNewPictureService - сервис добавления новой картины
     */
    private ArrivalNewPictureService $arrivalNewPictureService;

    /**
     * @var EntityManagerInterface - соединение с бд
     */
    private EntityManagerInterface $em;

    /**
     * @param SearchPicturesService    $searchPicturesService    - сервис поиска картин
     * @param ArrivalNewPictureService $arrivalNewPictureService - сервис добавления новой картины
     * @param EntityManagerInterface   $em                       - соединение с бд
     */
    public function __construct(
        SearchPicturesService $searchPicturesService,
        ArrivalNewPictureService $arrivalNewPictureService,
        EntityManagerInterface $em
    ) {
        $this->searchPicturesService = $searchPicturesService;
        $this->arrivalNewPictureService = $arrivalNewPictureService;
        $this->em = $em;
    }

    public function __invoke(Request $serverRequest): Response
    {
        $form = $this->createForm(CreatePictureForm::class);
        $form->handleRequest($serverRequest);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->create($form->getData());
            $form = $this->createForm(CreatePictureForm::class);
        }
        $template = 'picture.administration.twig';
        $dtoCollection = $this->searchPicturesService->search(new SearchPicturesService\SearchPicturesCriteria());
        $context = [
            'form_picture' => $form,
            'pictures'     => $dtoCollection
        ];
        return $this->renderForm(
            $template,
            $context
        );
    }

    /**
     * Создание картины в бд
     * @param array $dataToCreate
     * @return void
     */
    private function create(array $dataToCreate): void
    {
        try {
            $this->em->beginTransaction();
            $this->arrivalNewPictureService->registerPicture(
                new ArrivalNewPictureService\NewPictureDto(
                    $dataToCreate['name'],
                    $dataToCreate['painter_id'],
                    $dataToCreate['year'],
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
