<?php

namespace Sergo\ArtGallery\Form;

use Sergo\ArtGallery\Service\SearchTicketsService;
use Sergo\ArtGallery\Service\SearchVisitorsService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type as FormElement;

class CreateTicketReportForm extends AbstractType
{
    private SearchVisitorsService $visitorsService;
    private SearchTicketsService $ticketsService;

    /**
     * @param SearchVisitorsService $visitorsService
     * @param SearchTicketsService  $ticketsService
     */
    public function __construct(SearchVisitorsService $visitorsService, SearchTicketsService $ticketsService)
    {
        $this->visitorsService = $visitorsService;
        $this->ticketsService = $ticketsService;
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
            'ticket_id',
            FormElement\ChoiceType::class,
            [
                'required'     => true,
                'multiple'     => true,
                'label'        => 'Билет в галерею',
                'choices'      => $this->ticketsService->search(new SearchTicketsService\SearchTicketsCriteria()),
                'choice_label' => static function (SearchTicketsService\TicketDto $ticketDto): string {
                    return $ticketDto->getGallery()->getName() . ' ' . $ticketDto->getCost(
                        ) . ' ' . $ticketDto->getCurrency();
                },
                'choice_value' => static function (SearchTicketsService\TicketDto $ticketDto): string {
                    return $ticketDto->getId();
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
            'submit',
            FormElement\SubmitType::class,
            [
                'label'    => 'Добавить',
                'priority' => 100
            ]
        )->setMethod('POST');
        $builder->get('ticket_id')->addModelTransformer(
            new CallbackTransformer(
                static function ($tickets) {
                    return $tickets;
                },
                static function ($tickets) {
//                    return array_map(static function (SearchTicketsService\TicketDto $ticketDto): int {
//                        return $ticketDto->getId();
//                    }, $tickets);
                    return array_shift($tickets)->getId();
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
        parent::buildForm($builder, $options);
    }

}