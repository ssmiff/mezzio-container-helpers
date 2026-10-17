<?php

declare(strict_types=1);

namespace Ssmiff\MezzioContainerHelpers\Test;

use InvalidArgumentException;
use LogicException;
use Ssmiff\MezzioContainerHelpers\Resolver\ConstructorResolver;
use Ssmiff\MezzioContainerHelpers\Services;
use Ssmiff\MezzioContainerHelpers\Test\Fixture\Greeter;
use Ssmiff\MezzioContainerHelpers\Test\Fixture\NeedsCustomAttribute;
use Ssmiff\MezzioContainerHelpers\Test\Fixture\NeedsMixed;

final class ConstructorResolverTest extends TestCase
{
    public function testPlansAreCachedInMemory(): void
    {
        $resolver = new ConstructorResolver();

        self::assertSame($resolver->plan(NeedsMixed::class), $resolver->plan(NeedsMixed::class));
    }

    public function testResolverIsASharedContainerService(): void
    {
        $container = $this->container();

        self::assertSame(
            Services::get($container, ConstructorResolver::class),
            Services::get($container, ConstructorResolver::class),
        );
    }

    public function testNamedArgumentsMixAttributesTypesAndOverrides(): void
    {
        $container = $this->container(['name' => 'demo']);

        $object = new ConstructorResolver()->plan(NeedsMixed::class)->instantiate($container, ['apiKey' => 'secret']);

        self::assertSame(Services::get($container, Greeter::class), $object->greeter);
        self::assertSame('demo', $object->appName);
        self::assertSame('secret', $object->apiKey);
        self::assertSame(3, $object->retries);
    }

    public function testOverridesWinOverAttributesAndDefaults(): void
    {
        $object = new ConstructorResolver()->plan(NeedsMixed::class)->instantiate(
            $this->container(['name' => 'demo']),
            ['apiKey' => 'k', 'appName' => 'forced', 'retries' => 9],
        );

        self::assertSame('forced', $object->appName);
        self::assertSame(9, $object->retries);
    }

    public function testConfigValueFallsBackToItsDefault(): void
    {
        $plan = new ConstructorResolver()->plan(NeedsMixed::class);
        $object = $plan->instantiate($this->container(), ['apiKey' => 'k']);

        self::assertSame('fallback', $object->appName);
    }

    public function testParameterWithoutSourceNeedsAnOverride(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('NeedsMixed::$apiKey has no class type or attribute');

        new ConstructorResolver()->plan(NeedsMixed::class)->instantiate($this->container());
    }

    public function testUnknownOverrideIsRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('no constructor parameter named apiKye');

        new ConstructorResolver()->plan(NeedsMixed::class)->instantiate($this->container(), ['apiKye' => 'typo']);
    }

    public function testCustomAttributesImplementingParameterSourceAreHonoured(): void
    {
        $container = $this->container();
        $object    = new ConstructorResolver()->plan(NeedsCustomAttribute::class)->instantiate($container);

        self::assertSame(Services::get($container, Greeter::class), $object->greeter);
    }

    public function testArgumentsAreKeyedByParameterName(): void
    {
        $plan = new ConstructorResolver()->plan(NeedsMixed::class);
        $arguments = $plan->arguments($this->container(), ['apiKey' => 'k']);

        self::assertSame(['greeter', 'appName', 'apiKey'], array_keys($arguments));
    }
}
