<?php

declare(strict_types=1);

namespace Ssmiff\MezzioContainerHelpers\Resolver;

use InvalidArgumentException;
use LogicException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use Ssmiff\MezzioContainerHelpers\Attribute\ParameterSource;

use function array_diff_key;
use function array_key_exists;
use function array_keys;
use function implode;
use function in_array;
use function sprintf;

/** @template T of object */
final readonly class ConstructorPlan
{
    /**
     * @param class-string<T> $class
     * @param array<string, ParameterSource|null> $sources
     * @param list<string> $optional
     */
    public function __construct(public string $class, public array $sources, public array $optional = [])
    {
    }

    /**
     * @param array<string, mixed> $overrides
     * @return array<string, mixed>
     */
    public function arguments(ContainerInterface $container, array $overrides = []): array
    {
        $unknown = array_diff_key($overrides, $this->sources);
        if ($unknown !== []) {
            throw new InvalidArgumentException(sprintf(
                '%s has no constructor parameter named %s.',
                $this->class,
                implode(', ', array_keys($unknown)),
            ));
        }

        $arguments = [];
        foreach ($this->sources as $name => $source) {
            if (array_key_exists($name, $overrides)) {
                $arguments[$name] = $overrides[$name];
            } elseif ($source !== null) {
                $arguments[$name] = $source->resolve($container);
            } elseif (! in_array($name, $this->optional, true)) {
                throw new LogicException(sprintf(
                    '%s::$%s has no class type or attribute; pass a value for it in the overrides.',
                    $this->class,
                    $name,
                ));
            }
        }

        return $arguments;
    }

    /**
     * @param array<string, mixed> $overrides
     * @return T
     */
    public function instantiate(ContainerInterface $container, array $overrides = []): object
    {
        return new ReflectionClass($this->class)->newInstanceArgs($this->arguments($container, $overrides));
    }
}
