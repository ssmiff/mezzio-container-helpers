<?php

declare(strict_types=1);

namespace Ssmiff\MezzioContainerHelpers\Test\Fixture;

use Ssmiff\MezzioContainerHelpers\Attribute\ConfigValue;

final readonly class NeedsMixed
{
    public function __construct(
        public Greeter $greeter,
        #[ConfigValue('app.name', 'fallback')]
        public string $appName,
        public string $apiKey,
        public int $retries = 3,
    ) {}
}
