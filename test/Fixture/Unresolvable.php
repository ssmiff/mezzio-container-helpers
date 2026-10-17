<?php

declare(strict_types=1);

namespace Ssmiff\MezzioContainerHelpers\Test\Fixture;

final readonly class Unresolvable
{
    public function __construct(public string $name)
    {
    }
}
