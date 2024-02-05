<?php

namespace Sergo\ArtGallery\Form;

use Sergo\ArtGallery\Service\ReadCurrencyNames;
use Sergo\ArtGallery\Service\SearchPicturesService;
use Sergo\ArtGallery\Service\SearchVisitorsService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type as FormElement;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreatePictureReportForm extends AbstractType
{
    private SearchPicturesService $picturesService;
    private SearchVisitorsService $visitorsService;
    private ReadCurrencyNames $currencyNames;
    /**
     * @param SearchPicturesService $picturesService
     * @param SearchVisitorsService $visitorsService
     * @param ReadCurrencyNames     $currencyNames
     */
    public function __construct(
        SearchPicturesService $picturesService,
        SearchVisitorsService $visitorsService,
        ReadCurrencyNames $currencyNames
    )
    {
        $this->picturesService = $picturesService;
        $this->visitorsService = $visitorsService;
        $this->currencyNames = $currencyNames;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'dateOfPurchase',
            FormElement\TextType::class,
            [
                'required'    => true,
                'label'       => 'Дата покупки',
                'priority'    => 400,
                'constraints' => [
                    new Assert\Type([
                        'type' => 'string'
                    ]),
                    new Assert\NotBlank(
                        ['normalizer' => 'trim']
                    ),
                    new Assert\Length([
                        'min' => 1,
                        'max' => 250
                    ])
                ]
            ]
        )->add(
            'cost',
            FormElement\IntegerType::class,
            [
                'required'    => true,
                'label'       => 'Цена',
                'priority'    => 300,
                'constraints' => [
                    new Assert\Type(['type' => 'int']),
                    new Assert\PositiveOrZero(),
                ],
            ]
        )->add(
            'picture_id',
            FormElement\ChoiceType::class,
            [
                'required'     => true,
                'multiple'     => true,
                'label'        => 'Картина',
                'choices'      => $this->picturesService->search(new SearchPicturesService\SearchPicturesCriteria()),
                'choice_label' => static function (SearchPicturesService\PictureDto $pictureDto): string {
                    return $pictureDto->getName();
                },
                'choice_value' => static function (SearchPicturesService\PictureDto $pictureDto): string {
                    return $pictureDto->getId();
                },
                'priority'     => 2000
            ]
        )->add(
            'visitor_id',
            FormElement\ChoiceType::class,
            [
                'required'     => true,
                'multiple'     => true,
                'label'        => 'Покупатель',
                'choices'      => $this->visitorsService->search(new SearchVisitorsService\SearchVisitorCriteria()),
                'choice_label' => static function (SearchVisitorsService\VisitorDto $visitorDto): string {
                    return $visitorDto->getFullName();
                },
                'choice_value' => static function (SearchVisitorsService\VisitorDto $visitorDto): string {
                    return $visitorDto->getId();
                },
                'priority'     => 2100
            ]
        )->add(
            'currency',
            FormElement\ChoiceType::class,
            [
                'required'     => true,
                'multiple'     => true,
                'label'        => 'Валюта',
                'choices'      => $this->currencyNames->returnCurrencyNames(),
                'choice_label' => static function (string $name): string {
                    return $name;
                },
                'priority'     => 210
            ]
        )->add(
            'submit',
            FormElement\SubmitType::class,
            [
                'label'    => 'Добавить',
                'priority' => 100
            ]
        )->setMethod('POST');
        $builder->get('picture_id')->addModelTransformer(
            new CallbackTransformer(
                static function ($pictures) {
                    return $pictures;
                },
                static function ($pictures) {
//                    return array_map(static function (SearchPicturesService\PictureDto $pictureDto): int {
//                        return $pictureDto->getId();
//                    }, $pictures);
                    return array_shift($pictures)->getId();
                }
            )
        );
        $builder->get('visitor_id')->addModelTransformer(
            new CallbackTransformer(
                static function ($visitors) {
                    return $visitors;
                },
                static function ($visitors) {
//                    return array_map(static function (SearchVisitorsService\VisitorDto $visitorDto): int {
//                        return $visitorDto->getId();
//                    }, $visitors);
                    return array_shift($visitors)->getId();
                }
            )
        );
        $builder->get('currency')->addModelTransformer(
            new CallbackTransformer(
                static function ($names) {
                    return $names;
                },
                static function ($names) {
                    return array_shift($names);
                }
            )
        );
        parent::buildForm($builder, $options);
    }
}