<?php

declare(strict_types=1);

namespace Ssmiff\MezzioContainerHelpers\Test;

use LogicException;
use Ssmiff\MezzioContainerHelpers\Factory\AttributeFactory;
use Ssmiff\MezzioContainerHelpers\Services;
use Ssmiff\MezzioContainerHelpers\Test\Fixture\Greeter;
use Ssmiff\MezzioContainerHelpers\Test\Fixture\NeedsCustomAttribute;
use Ssmiff\MezzioContainerHelpers\Test\Fixture\Unresolvable;

final class AttributeFactoryTest extends TestCase
{
    public function testBuildsTheRequestedClassFromItsAttributes(): void
    {
        $container = $this->container();

        $object = new AttributeFactory()($container, NeedsCustomAttribute::class);

        self::assertInstanceOf(NeedsCustomAttribute::class, $object);
        self::assertSame(Services::get($container, Greeter::class), $object->greeter);
    }

    public function testParameterWithoutTypeOrAttributeIsRejected(): void
    {
        $this->expectException(LogicException::class);

        new AttributeFactory()($this->container(), Unresolvable::class);
    }
}
