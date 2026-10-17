<?php

declare(strict_types=1);

namespace Ssmiff\MezzioContainerHelpers;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Ssmiff\MezzioContainerHelpers\Resolver\ConstructorResolver;

final readonly class ConfigProvider
{
    /** @return array{dependencies: array{factories: array<string, class-string<FactoryInterface>>}} */
    public function __invoke(): array
    {
        return ['dependencies' => $this->dependencies()];
    }

    /** @return array{factories: array<string, class-string<FactoryInterface>>} */
    public function dependencies(): array
    {
        return ['factories' => [ConstructorResolver::class => InvokableFactory::class]];
    }
}
