<?php

declare(strict_types=1);

namespace Ssmiff\MezzioContainerHelpers\Test\Fixture;

final readonly class NeedsCustomAttribute
{
    public function __construct(
        #[UseGreeter]
        public object $greeter,
    ) {
    }
}
