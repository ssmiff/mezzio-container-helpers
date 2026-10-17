<?php

declare(strict_types=1);

namespace Ssmiff\MezzioContainerHelpers\Resolver;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use Ssmiff\MezzioContainerHelpers\Attribute\ParameterSource;

final class ConstructorResolver
{
    /** @var array<class-string, ConstructorPlan<object>> */
    private array $plans = [];

    /**
     * @template T of object
     * @param class-string<T> $class
     * @return ConstructorPlan<T>
     */
    public function plan(string $class): ConstructorPlan
    {
        /** @var ConstructorPlan<T> */
        return $this->plans[$class] ??= $this->analyse($class);
    }

    /**
     * @template T of object
     * @param class-string<T> $class
     * @return ConstructorPlan<T>
     */
    private function analyse(string $class): ConstructorPlan
    {
        $sources  = [];
        $optional = [];

        foreach (new ReflectionClass($class)->getConstructor()?->getParameters() ?? [] as $parameter) {
            $sources[$parameter->getName()] = self::source($parameter);

            if ($sources[$parameter->getName()] === null && $parameter->isDefaultValueAvailable()) {
                $optional[] = $parameter->getName();
            }
        }

        return new ConstructorPlan($class, $sources, $optional);
    }

    private static function source(ReflectionParameter $parameter): ParameterSource|null
    {
        foreach ($parameter->getAttributes(ParameterSource::class, ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
            return $attribute->newInstance();
        }

        $type = $parameter->getType();

        return $type instanceof ReflectionNamedType && ! $type->isBuiltin()
            ? new ContainerService($type->getName())
            : null;
    }
}
