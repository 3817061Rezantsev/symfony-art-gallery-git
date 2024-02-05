<?php

namespace Sergo\ArtGalleryTest;

use Exception;
use PHPUnit\Framework\TestCase;
use Sergo\ArtGallery\Config\AppConfig;
use Sergo\ArtGallery\Config\ContainerExtensions;
use Sergo\ArtGallery\Infrastructure\DI\SymfonyDiContainerInit;

class DiAppConfigServiceTest extends TestCase
{
    public static function appConfigDataProvider(): array
    {
        return [
            'hideErrorMessage' => [
                'method' => 'isHideErrorMessage',
                'expectedValue' => false,
                'isPath' => false
            ],
            'loginUri' => [
                'method' => 'getLoginUri',
                'expectedValue' => '/login',
                'isPath' => false
            ],
        ];
    }
    /**
     * @dataProvider appConfigDataProvider
     * @throws Exception
     */
    public function testAppConfigGetter(string $method, $expectedValue, bool $isPath): void
    {
        $diContFactory = new SymfonyDiContainerInit(
            new SymfonyDiContainerInit\ContainerParams(
                __DIR__  . '/../config/dev/di.xml',
                ['kernel.project_dir' => __DIR__ . '/../'],
                ContainerExtensions::httpAppContainerExtensions()
            )
        );
        $diContainer = $diContFactory();
        $appConfig = $diContainer->get(AppConfig::class);
        $actualValue = $appConfig->$method();
        if ($isPath) {
            $expectedValue = realpath($expectedValue);
            $actualValue = realpath($actualValue);
        }
        $this->assertSame($expectedValue, $actualValue);
    }
}
