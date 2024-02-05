<?php

namespace Sergo\ArtGallery\Repository\EntityToArray;

use Sergo\ArtGallery\Entity\Picture;
use Sergo\ArtGallery\Entity\PicturePurchaseReport;
use Sergo\ArtGallery\Entity\Ticket;
use Sergo\ArtGallery\Entity\TicketPurchaseReport;

/**
 * Класс, превращающий сущности в массивы
 */
class EntityToArray
{
    public static function pictureToArray(Picture $picture): array
    {
        return [
            'id' => $picture->getId(),
            'name' => $picture->getName(),
            'painter_id' => $picture->getPainter()->getId(),
            'painter_fullName' => $picture->getPainter()->getFullName(),
            'painter_dateOfBirth' => $picture->getPainter()->getDateOfBirth(),
            'painter_telephoneNumber' => $picture->getPainter()->getTelephoneNumber(),
            'year' => $picture->getYear(),
        ];
    }

    public static function pictureReportToArray(PicturePurchaseReport $pictureReport): array
    {
        return [
            'id' => $pictureReport->getId(),
            'cost' => $pictureReport->getPurchasePrice()->getMoney()->getAmount(),
            'dateOfPurchase' => $pictureReport->getPurchasePrice()->getDate()->format('Y-m-d H:i'),
            'currency' => $pictureReport->getPurchasePrice()->getMoney()->getCurrency()->getName(),
            'visitor_id' => $pictureReport->getVisitor()->getId(),
            'visitor_fullName' => $pictureReport->getVisitor()->getFullName(),
            'visitor_dateOfBirth' => $pictureReport->getVisitor()->getDateOfBirth(),
            'visitor_telephoneNumber' => $pictureReport->getVisitor()->getTelephoneNumber(),
            'picture_id' => $pictureReport->getPicture()->getId(),
            'picture_name' => $pictureReport->getPicture()->getName(),
            'picture_painter_id' => $pictureReport->getPicture()->getPainter()->getId(),
            'picture_painter_fullName' => $pictureReport->getPicture()->getPainter()->getFullName(),
            'picture_painter_dateOfBirth' => $pictureReport->getPicture()->getPainter()->getDateOfBirth(),
            'picture_painter_telephoneNumber' => $pictureReport->getPicture()->getPainter()->getTelephoneNumber(),
            'picture_year' => $pictureReport->getPicture()->getYear(),
        ];
    }

    public static function ticketToArray(Ticket $ticket): array
    {
        return [
            'id' => $ticket->getId(),
            'dateOfVisit' => $ticket->getDateOfVisit(),
            'cost' => $ticket->getCost(),
            'currency' => $ticket->getCurrency(),
            'gallery_id' => $ticket->getGallery()->getId(),
            'gallery_name' => $ticket->getGallery()->getName(),
            'gallery_address' => $ticket->getGallery()->getAddress()
        ];
    }
    public static function ticketReportToArray(TicketPurchaseReport $ticketReport): array
    {
        return
            [
                'id' => $ticketReport->getId(),
                'dateOfPurchase' => $ticketReport->getPurchasePrice()->getDate()->format('Y-m-d H:i'),
                'currency' => $ticketReport->getPurchasePrice()->getMoney()->getCurrency()->getName(),
                'ticket_id' => $ticketReport->getTicket()->getId(),
                'visitor_id' => $ticketReport->getVisitor()->getId(),
                'visitor_fullName' => $ticketReport->getVisitor()->getFullName(),
                'visitor_dateOfBirth' => $ticketReport->getVisitor()->getDateOfBirth(),
                'visitor_telephoneNumber' => $ticketReport->getVisitor()->getTelephoneNumber(),
                'cost' => $ticketReport->getPurchasePrice()->getMoney()->getAmount(),
                'ticket_dateOfVisit' => $ticketReport->getTicket()->getDateOfVisit(),
                'ticket_gallery_name' => $ticketReport->getTicket()->getGallery()->getName(),
                'ticket_gallery_address' => $ticketReport->getTicket()->getGallery()->getAddress(),
                'ticket_gallery_id' => $ticketReport->getTicket()->getGallery()->getId(),
                'ticket_cost' => $ticketReport->getTicket()->getCost(),
                'ticket_currency' => $ticketReport->getTicket()->getCurrency(),
            ];
    }
}
