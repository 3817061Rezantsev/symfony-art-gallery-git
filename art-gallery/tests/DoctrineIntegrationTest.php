<?php

namespace Sergo\ArtGalleryTest;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Exception;
use PHPUnit\Framework\TestCase;
use Sergo\ArtGallery\Config\ContainerExtensions;
use Sergo\ArtGallery\Entity\Admin;
use Sergo\ArtGallery\Entity\Gallery;
use Sergo\ArtGallery\Entity\Painter;
use Sergo\ArtGallery\Entity\Picture;
use Sergo\ArtGallery\Entity\PicturePurchaseReport;
use Sergo\ArtGallery\Entity\TicketPurchaseReport;
use Sergo\ArtGallery\Entity\Visitor;
use Sergo\ArtGallery\Infrastructure\DI\SymfonyDiContainerInit;
use Sergo\ArtGallery\ValueObject\Currency;

class DoctrineIntegrationTest extends TestCase
{
    /**
     * Создание экземпляра EntityManagerInterface
     * @throws Exception
     */
    public function testCreateEntityManager(): void
    {
        $diContainerFactory = new SymfonyDiContainerInit(
            new SymfonyDiContainerInit\ContainerParams(
                __DIR__ . '/../config/dev/di.xml',
                ['kernel.project_dir' => __DIR__ . '/../'],
                ContainerExtensions::httpAppContainerExtensions()
            )
        );

        $diContainer = $diContainerFactory();
        $en = $diContainer->get(EntityManagerInterface::class);
        $this->assertInstanceOf(EntityManagerInterface::class, $en);
    }

    /**
     * Загрузка экземпляра класса Gallery
     * @throws Exception
     */
    public function testLoadGallery(): void
    {
        $diContainerFactory = new SymfonyDiContainerInit(
            new SymfonyDiContainerInit\ContainerParams(
                __DIR__ . '/../config/dev/di.xml',
                ['kernel.project_dir' => __DIR__ . '/../'],
                ContainerExtensions::httpAppContainerExtensions()
            )
        );

        $diContainer = $diContainerFactory();
        $en = $diContainer->get(EntityManagerInterface::class);
        $gallery = $en->getRepository(Gallery::class)->findOneBy(['id' => '1']);
        $this->assertInstanceOf(Gallery::class, $gallery);
    }

    /**
     * Загрузка экземпляра класса AdminDataProvider
     * @throws Exception
     */
    public function testLoadAdminDataProvider(): void
    {
        $diContainerFactory = new SymfonyDiContainerInit(
            new SymfonyDiContainerInit\ContainerParams(
                __DIR__ . '/../config/dev/di.xml',
                ['kernel.project_dir' => __DIR__ . '/../'],
                ContainerExtensions::httpAppContainerExtensions()
            )
        );

        $diContainer = $diContainerFactory();
        $en = $diContainer->get(EntityManagerInterface::class);
        $entity = $en->getRepository(Admin::class)->findOneBy(['login' => 'admin']);
        $this->assertInstanceOf(Admin::class, $entity);
    }

    /**
     * Загрузка экземпляра класса Visitor
     * @throws Exception
     */
    public function testLoadVisitor(): void
    {
        $diContainerFactory = new SymfonyDiContainerInit(
            new SymfonyDiContainerInit\ContainerParams(
                __DIR__ . '/../config/dev/di.xml',
                ['kernel.project_dir' => __DIR__ . '/../'],
                ContainerExtensions::httpAppContainerExtensions()
            )
        );

        $diContainer = $diContainerFactory();
        $en = $diContainer->get(EntityManagerInterface::class);
        $entity = $en->getRepository(Visitor::class)->findOneBy(['id' => '10']);
        $this->assertInstanceOf(Visitor::class, $entity);
    }

    /**
     * Загрузка экземпляра класса Painter
     * @throws Exception
     */
    public function testLoadPainter(): void
    {
        $diContainerFactory = new SymfonyDiContainerInit(
            new SymfonyDiContainerInit\ContainerParams(
                __DIR__ . '/../config/dev/di.xml',
                ['kernel.project_dir' => __DIR__ . '/../'],
                ContainerExtensions::httpAppContainerExtensions()
            )
        );

        $diContainer = $diContainerFactory();
        $en = $diContainer->get(EntityManagerInterface::class);
        $entity = $en->getRepository(Painter::class)->findOneBy(['id' => '1']);
        $this->assertInstanceOf(Painter::class, $entity);
    }

    /**
     * Загрузка экземпляра класса Currency
     * @throws Exception
     */
    public function testLoadCurrency(): void
    {
        $diContainerFactory = new SymfonyDiContainerInit(
            new SymfonyDiContainerInit\ContainerParams(
                __DIR__ . '/../config/dev/di.xml',
                ['kernel.project_dir' => __DIR__ . '/../'],
                ContainerExtensions::httpAppContainerExtensions()
            )
        );

        $diContainer = $diContainerFactory();
        $en = $diContainer->get(EntityManagerInterface::class);
        $entity = $en->getRepository(Currency::class)->findOneBy(['id' => '1']);
        $this->assertInstanceOf(Currency::class, $entity);
    }

    /**
     * Загрузка экземпляра класса Picture
     * @throws Exception
     */
    public function testLoadPicture(): void
    {
        $diContainerFactory = new SymfonyDiContainerInit(
            new SymfonyDiContainerInit\ContainerParams(
                __DIR__ . '/../config/dev/di.xml',
                ['kernel.project_dir' => __DIR__ . '/../'],
                ContainerExtensions::httpAppContainerExtensions()
            )
        );

        $diContainer = $diContainerFactory();
        $en = $diContainer->get(EntityManagerInterface::class);
        $entity = $en->getRepository(Picture::class)->findOneBy(['id' => '1']);
        $painter = $entity->getPainter();
        $this->assertInstanceOf(Picture::class, $entity);
        $this->assertInstanceOf(Painter::class, $painter);
    }

    /**
     * Загрузка экземпляра класса PicturePurchaseReport
     * @throws Exception
     */
    public function testLoadPicturePurchaseReport(): void
    {
        $diContainerFactory = new SymfonyDiContainerInit(
            new SymfonyDiContainerInit\ContainerParams(
                __DIR__ . '/../config/dev/di.xml',
                ['kernel.project_dir' => __DIR__ . '/../'],
                ContainerExtensions::httpAppContainerExtensions()
            )
        );

        $diContainer = $diContainerFactory();
        $en = $diContainer->get(EntityManagerInterface::class);
        /** @var PicturePurchaseReport $entity */
        $entity = $en->getRepository(PicturePurchaseReport::class)->findOneBy(['id' => '1']);
        $picture = $entity->getPicture();
        $painter = $picture->getPainter();
        $visitor = $entity->getVisitor();
        $purchasePrice = $entity->getPurchasePrice();
        $this->assertInstanceOf(PicturePurchaseReport::class, $entity);
    }

    /**
     * Загрузка экземпляра класса TicketPurchaseReport
     * @throws Exception
     */
    public function testLoadTicketPurchaseReport(): void
    {
        $diContainerFactory = new SymfonyDiContainerInit(
            new SymfonyDiContainerInit\ContainerParams(
                __DIR__ . '/../config/dev/di.xml',
                ['kernel.project_dir' => __DIR__ . '/../'],
                ContainerExtensions::httpAppContainerExtensions()
            )
        );

        $diContainer = $diContainerFactory();
        $en = $diContainer->get(EntityManagerInterface::class);
        /** @var TicketPurchaseReport $entity */
        $entity = $en->getRepository(TicketPurchaseReport::class)->findOneBy(['id' => '1']);
        $ticket = $entity->getTicket();
        $gallery = $ticket->getGallery();
        $visitor = $entity->getVisitor();
        $purchasePrice = $entity->getPurchasePrice();
        $this->assertInstanceOf(TicketPurchaseReport::class, $entity);
    }

    /**
     * Проверка работы обработчика событий
     * @throws Exception
     */
    public function testDoctrineEventSubscriber(): void
    {
        $diContainerFactory = new SymfonyDiContainerInit(
            new SymfonyDiContainerInit\ContainerParams(
                __DIR__  . '/../config/dev/di.xml',
                ['kernel.project_dir' => __DIR__ . '/../'],
                ContainerExtensions::httpAppContainerExtensions()
            )
        );

        $diContainer = $diContainerFactory();
        /** @var EntityManager $en */
        $en = $diContainer->get(EntityManagerInterface::class);
        $eventSubscriber = new class implements EventSubscriber {
            public $args;
            public function getSubscribedEvents(): array
            {
                return [Events::postLoad];
            }
            public function postLoad($args): void
            {
                $this->args = $args;
            }
        };
        $en->getEventManager()->addEventSubscriber($eventSubscriber);
        $gallery = $en->getRepository(Gallery::class)->findOneBy(['name' => 'Картинная галерея Лувра']);
        $this->assertInstanceOf(Gallery::class, $gallery);
        $this->assertInstanceOf(LifecycleEventArgs::class, $eventSubscriber->args);
        $this->assertEquals($gallery, $eventSubscriber->args->getEntity());
    }

    /**
     * DQL заброс для поиска
     * @throws Exception
     */
    public function testLearnDql(): void
    {
        $diContainerFactory = new SymfonyDiContainerInit(
            new SymfonyDiContainerInit\ContainerParams(
                __DIR__  . '/../config/dev/di.xml',
                ['kernel.project_dir' => __DIR__ . '/../'],
                ContainerExtensions::httpAppContainerExtensions()
            )
        );

        $diContainer = $diContainerFactory();
        /** @var EntityManager $em */
        $em = $diContainer->get(EntityManagerInterface::class);
        $dql = <<<EOF
    SELECT g 
    FROM \Sergo\ArtGallery\Entity\Gallery as g 
    WHERE g.name = :name
EOF;
        $query = $em->createQuery($dql);
        $query->setParameter('name', 'Картинная галерея Лувра');
        /** @var Gallery $entity  */
        $entity = $query->getSingleResult();
        $this->assertEquals('Rue de Rivoli, 75001 Paris, Франция', $entity->getAddress());
        $this->assertEquals('Картинная галерея Лувра', $entity->getName());
    }
}
