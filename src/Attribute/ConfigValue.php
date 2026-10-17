<?php

declare(strict_types=1);

namespace Ssmiff\MezzioContainerHelpers\Attribute;

use Attribute;
use Override;
use Psr\Container\ContainerInterface;
use Ssmiff\MezzioContainerHelpers\Support\Config;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_PROPERTY)]
final readonly class ConfigValue implements ParameterSource
{
    public function __construct(public string $path, public mixed $default = null) {}

    #[Override]
    public function resolve(ContainerInterface $container): mixed
    {
        return Config::get($container, $this->path, $this->default);
    }
}
