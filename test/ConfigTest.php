<?php

declare(strict_types=1);

namespace Ssmiff\MezzioContainerHelpers\Test;

use Ssmiff\MezzioContainerHelpers\Support\Config;

final class ConfigTest extends TestCase
{
    public function testReadsDottedPaths(): void
    {
        $container = $this->container(['mail' => ['from' => 'a@example.com']]);

        self::assertSame('a@example.com', Config::get($container, 'app.mail.from'));
        self::assertSame(['from' => 'a@example.com'], Config::get($container, 'app.mail'));
    }

    public function testMissingPathsReturnTheDefault(): void
    {
        $container = $this->container();

        self::assertSame('x', Config::get($container, 'app.missing.deep', 'x'));
        self::assertNull(Config::get($container, 'nope'));
    }
}
