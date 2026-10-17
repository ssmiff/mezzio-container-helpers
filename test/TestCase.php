<?php

declare(strict_types=1);

namespace Ssmiff\MezzioContainerHelpers\Test;

use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase as PhpUnitTestCase;
use Ssmiff\MezzioContainerHelpers\ConfigProvider;
use Ssmiff\MezzioContainerHelpers\Test\Fixture\Greeter;

abstract class TestCase extends PhpUnitTestCase
{
    /** @param array<array-key, mixed> $app */
    protected function container(array $app = []): ServiceManager
    {
        $dependencies                        = new ConfigProvider()->dependencies();
        $dependencies['factories'][Greeter::class] = InvokableFactory::class;

        $container = new ServiceManager($dependencies);
        $container->setService('config', ['app' => $app]);

        return $container;
    }
}
