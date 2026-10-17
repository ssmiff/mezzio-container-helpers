<?php

declare(strict_types=1);

namespace Ssmiff\MezzioContainerHelpers;

use Psr\Container\ContainerInterface;
use UnexpectedValueException;

use function get_debug_type;
use function sprintf;

final readonly class Services
{
    /**
     * @template T of object
     * @param class-string<T> $type
     * @return T
     */
    public static function get(ContainerInterface $container, string $type, string|null $id = null): object
    {
        $id ??= $type;

        return self::expect($container->get($id), $type, $id);
    }

    /**
     * @template T of object
     * @param class-string<T> $type
     * @return T
     */
    private static function expect(mixed $service, string $type, string $id): object
    {
        return $service instanceof $type
            ? $service
            : throw new UnexpectedValueException(sprintf(
                'Service "%s" must be an instance of %s, %s given.',
                $id,
                $type,
                get_debug_type($service),
            ));
    }
}
