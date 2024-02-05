<?php

namespace Sergo\ArtGallery\DoctrineEventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;

/**
 * Подписчик на события связанные с сущностями
 */
class EntityEventSubscriber implements EventSubscriber
{
    /**
     * Логгер
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param LoggerInterface $logger - Логгер
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    /**
     * @inheritDoc
     */
    public function getSubscribedEvents(): array
    {
        return [];
    }

//    /**
//     * Обработчик события загрузки сущностей
//     * @param LifecycleEventArgs $args
//     * @return void
//     */
//    public function postLoad(LifecycleEventArgs $args): void
//    {
//        $this->logger->debug('Event postLoad' . get_class($args->getEntity()));
//    }
}
