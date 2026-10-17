<?php

declare(strict_types=1);

namespace Ssmiff\MezzioContainerHelpers\Factory;

use InvalidArgumentException;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Override;
use Psr\Container\ContainerInterface;
use Ssmiff\MezzioContainerHelpers\Resolver\ConstructorResolver;
use Ssmiff\MezzioContainerHelpers\Services;

use function class_exists;
use function sprintf;

final readonly class AttributeFactory implements FactoryInterface
{
    #[Override]
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): object
    {
        if (! class_exists($requestedName)) {
            throw new InvalidArgumentException(sprintf('Class %s does not exist.', $requestedName));
        }

        return Services::get($container, ConstructorResolver::class)->plan($requestedName)->instantiate($container);
    }
}
