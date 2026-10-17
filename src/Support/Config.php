<?php

declare(strict_types=1);

namespace Ssmiff\MezzioContainerHelpers\Support;

use Psr\Container\ContainerInterface;

use function array_key_exists;
use function array_slice;
use function explode;
use function is_array;

final readonly class Config
{
    public static function get(ContainerInterface $container, string $path, mixed $default = null): mixed
    {
        return self::descend($container->get('config'), explode('.', $path), $default);
    }

    /** @param list<string> $segments */
    private static function descend(mixed $node, array $segments, mixed $default): mixed
    {
        if ($segments === []) {
            return $node;
        }

        return is_array($node) && array_key_exists($segments[0], $node)
            ? self::descend($node[$segments[0]], array_slice($segments, 1), $default)
            : $default;
    }
}
