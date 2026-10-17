<?php

declare(strict_types=1);

namespace Ssmiff\MezzioContainerHelpers\Attribute;

use Psr\Container\ContainerInterface;

interface ParameterSource
{
    public function resolve(ContainerInterface $container): mixed;
}
