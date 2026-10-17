<?php

declare(strict_types=1);

namespace Ssmiff\MezzioContainerHelpers\Test\Fixture;

final class Greeter
{
    public function greet(string $name): string
    {
        return 'Hello ' . $name;
    }
}
