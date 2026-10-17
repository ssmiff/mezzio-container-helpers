<?php

declare(strict_types=1);

namespace Ssmiff\MezzioContainerHelpers\Test\Fixture;

use Attribute;
use Override;
use Psr\Container\ContainerInterface;
use Ssmiff\MezzioContainerHelpers\Attribute\ParameterSource;

#[Attribute(Attribute::TARGET_PARAMETER)]
final readonly class UseGreeter implements ParameterSource
{
    #[Override]
    public function resolve(ContainerInterface $container): mixed
    {
        return $container->get(Greeter::class);
    }
}
