<?php

declare(strict_types=1);

namespace Ssmiff\MezzioContainerHelpers\Resolver;

use Override;
use Psr\Container\ContainerInterface;
use Ssmiff\MezzioContainerHelpers\Attribute\ParameterSource;

final readonly class ContainerService implements ParameterSource
{
    public function __construct(public string $id) {}

    #[Override]
    public function resolve(ContainerInterface $container): mixed
    {
        return $container->get($this->id);
    }
}
