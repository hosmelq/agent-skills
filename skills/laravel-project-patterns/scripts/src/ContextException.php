<?php

declare(strict_types=1);

namespace LaravelProjectPatterns\Context;

use RuntimeException;

final class ContextException extends RuntimeException
{
    public function __construct(string $message, public readonly int $exitCode = 4)
    {
        parent::__construct($message);
    }
}
