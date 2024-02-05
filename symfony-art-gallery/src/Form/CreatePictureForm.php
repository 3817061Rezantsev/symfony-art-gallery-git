<?php

namespace Sergo\ArtGallery\Form;

use Sergo\ArtGallery\Service\SearchPaintersService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type as FormElement;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;


class CreatePictureForm extends AbstractType
{
    private SearchPaintersService $paintersService;

    /**
     * @param SearchPaintersService $paintersService
     */
    public function __construct(SearchPaintersService $paintersService)
    {
        $this->paintersService = $paintersService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'name',
            FormElement\TextType::class,
            [
                'required'    => true,
                'label'       => 'Название',
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
            'year',
            FormElement\TextType::class,
            [
                'required'    => true,
                'label'       => 'Год написания',
                'priority'    => 300,
                'constraints' => [
                    new Assert\Type(['type' => 'string']),
                    new Assert\NotBlank(
                        ['normalizer' => 'trim']
                    ),
                    new Assert\Length([
                        'min' => 1,
                        'max' => 4
                    ])
                ],
            ]
        )->add(
            'painter_id',
            FormElement\ChoiceType::class,
            [
                'required'     => true,
                'multiple'     => true,
                'label'        => 'Художник',
                'choices'      => $this->paintersService->search(new SearchPaintersService\SearchPainterCriteria()),
                'choice_label' => static function (SearchPaintersService\PainterDto $painterDto): string {
                    return $painterDto->getFullName();
                },
                'choice_value' => static function (SearchPaintersService\PainterDto $painterDto): string {
                    return $painterDto->getId();
                },
                'priority'     => 200
            ]
        )->add(
            'submit',
            FormElement\SubmitType::class,
            [
                'label'    => 'Добавить',
                'priority' => 100
            ]
        )->setMethod('POST');
        $builder->get('painter_id')->addModelTransformer(
            new CallbackTransformer(
                static function ($painters) {
                    return $painters;
                },
                static function ($painters) {
//                    return array_map(static function (SearchPaintersService\PainterDto $painterDto): int {
//                        return $painterDto->getId();
//                    }, $painters);
                    return array_shift($painters)->getId();
                }
            )
        );
        parent::buildForm($builder, $options);
    }


}