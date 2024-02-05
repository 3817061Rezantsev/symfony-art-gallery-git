<?php

namespace Sergo\ArtGalleryTest;

use Exception;
use JsonException;
use Nyholm\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Sergo\ArtGallery\Config\AppConfig;
use Sergo\ArtGallery\Config\ContainerExtensions;
use Sergo\ArtGallery\Infrastructure\DI\ContainerInterface;
use Sergo\ArtGallery\Infrastructure\DI\SymfonyDiContainerInit;
use Sergo\ArtGallery\Infrastructure\HttpApplication\App;
use Sergo\ArtGallery\Infrastructure\Router\RouterInterface;
use Sergo\ArtGallery\Infrastructure\View\NullRender;
use Sergo\ArtGallery\Infrastructure\View\RenderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class AppTest extends TestCase
{
    /**
     * @throws Exception
     */
    private static function createDiContainer(): ContainerBuilder
    {
        $containerBuilder = SymfonyDiContainerInit::createContainerBuilder(
            new SymfonyDiContainerInit\ContainerParams(
                __DIR__  . '/../config/dev/di.xml',
                ['kernel.project_dir' => __DIR__ . '/../'],
                ContainerExtensions::httpAppContainerExtensions()
            )
        );
        $containerBuilder->removeAlias(LoggerInterface::class);
        $containerBuilder->setDefinition(NullLogger::class, new Definition());
        $containerBuilder->setAlias(LoggerInterface::class, NullLogger::class)->setPublic(true);
        $containerBuilder->getDefinition(RenderInterface::class)
            ->setClass(NullRender::class)
            ->setArguments([]);
        return $containerBuilder;
    }

    /**
     * иллюстрация некорректной работы фабрики
     * @param array $config
     * @return string
     */
    public static function bugFactory(array $config): string
    {
        return 'Oops';
    }

    /**
     * @throws Exception
     */
    public static function dataProvider(): array
    {
        return [
            'Тестирование просмотра акта о покупке картин' => [
                'in' => [ //то что на входе
                    'uri' => '/picturePurchaseReport?id=5',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [  //то что на выходе
                    'httpCode' => 200,
                    'result' => [
                        [
                            'id' => 5,
                            'visitor' => [
                                'id' => 10,
                                'fullName' => 'Иванов Иван Александрович',
                                'dateOfBirth' => '2001.11.15',
                                'telephoneNumber' => '79011047564',
                            ],
                            'picture' => [
                                'id' => 4,
                                'name' => 'Девушка с жемчужной сережкой',
                                'painter' => [
                                    'id' => 8,
                                    'fullName' => 'Ян Вермеер',
                                    'dateOfBirth' => '1632.10.01',
                                    'telephoneNumber' => null
                                ],
                                'year' => '1665',
                            ],
                            'dateOfPurchase' => '2020-07-09 21:40',
                            'cost' => 100000,
                            'currency' => 'RUB'

                        ],
                    ]
                ]
            ],
            'Тестирование поиска книг по промежутку написания' => [
                'in' => [ //то что на входе
                    'uri' => '/picture?start=1900&end=1999',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [  //то что на выходе
                    'httpCode' => 200,
                    'result' =>
                        [

                            0 =>
                                [
                                    'id' => 2,
                                    'name' => 'Черный квадрат',
                                    'painter' => [
                                        'id' => 6,
                                        'fullName' => 'Казимир Малевич',
                                        'dateOfBirth' => '1879.02.23',
                                        'telephoneNumber' => null
                                    ],
                                    'year' => '1915',
                                ],
                            2 =>
                                [
                                    'id' => 6,
                                    'name' => 'Постоянство памяти',
                                    'painter' => [
                                        'id' => 12,
                                        'fullName' => 'Сальвадор Дали',
                                        'dateOfBirth' => '1904.05.11',
                                        'telephoneNumber' => null
                                    ],
                                    'year' => '1934',
                                ],
                            1 =>
                                [
                                    'id' => 8,
                                    'name' => 'Битва',
                                    'painter' => [
                                        'id' => 10,
                                        'fullName' => 'Наталья Тюнева',
                                        'dateOfBirth' => null,
                                        'telephoneNumber' => '89853187535'
                                    ],
                                    'year' => '1974',
                                ],

                        ],
                ]
            ],
            'Тестирование поиска акта о покупке билета' => [
                'in' => [ //то что на входе
                    'uri' => '/ticketPurchaseReport?id=1',
                    'diContainer' => (static function (ContainerBuilder $c): ContainerBuilder {
                        $c->compile();
                        return $c;
                    })(
                        self::createDiContainer()
                    )
                ],
                'out' => [  //то что на выходе
                    'httpCode' => 200,
                    'result' =>
                        [
                            0 =>
                                [
                                    'id' => 1,
                                    'visitor' =>
                                        [
                                            'id' => 10,
                                            'fullName' => 'Иванов Иван Александрович',
                                            'dateOfBirth' => '2001.11.15',
                                            'telephoneNumber' => '79011047564',
                                        ],
                                    'ticket' =>
                                        [
                                            'id' => 2,
                                            'gallery' =>
                                                [
                                                    'id' => 2,
                                                    'name' => 'Музей Метрополитен',
                                                    'address' => 'Нью-Йорк, Нью-Йорк, США',
                                                ],
                                            'dateOfVisit' => '2021.11.22',
                                            'cost' => 3000,
                                            'currency' => 'RUB'
                                        ],
                                    'dateOfPurchase' => '2020-10-03 14:15',
                                    'cost' => 3000,
                                    'currency' => 'RUB'
                                ],
                        ]
                ]
            ],
        ];
    }

    /**
     * @dataProvider dataProvider
     * @throws JsonException
     */
    public function testApp(array $in, array $out): void
    {
        $httpRequest = new \Nyholm\Psr7\ServerRequest(
            'GET',
            new Uri($in['uri']),
            ['Content-Type' => 'application/json']
        );
        $queryParams = [];
        parse_str($httpRequest->getUri()->getQuery(), $queryParams);
        $httpRequest = $httpRequest->withQueryParams($queryParams);
        //Arrange и Act
        $diContainer = $in['diContainer'];
        $httpResponse = (new App(
            static function (ContainerInterface $di): RouterInterface {
                return $di->get(RouterInterface::class);
            },
            static function (ContainerInterface $di): LoggerInterface {
                return $di->get(LoggerInterface::class);
            },
            static function (ContainerInterface $di): AppConfig {
                return $di->get(AppConfig::class);
            },
            static function (ContainerInterface $di): RenderInterface {
                return $di->get(RenderInterface::class);
            },
            static function () use ($diContainer): ContainerInterface {
                return $diContainer;
            }
        ))->dispatch($httpRequest);
        //Assert
        $this->assertEquals($out['httpCode'], $httpResponse->getStatusCode(), "код ответа");
        $this->assertEquals(
            $out['result'],
            json_decode($httpResponse->getBody(), true, 512, JSON_THROW_ON_ERROR),
            'Структура ответа'
        );
    }
}
