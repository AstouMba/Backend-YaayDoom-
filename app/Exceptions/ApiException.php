<?php

namespace App\Exceptions;

use RuntimeException;

class ApiException extends RuntimeException
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(
        public readonly string $type,
        public readonly string $key,
        public readonly int $status,
        public readonly array $context = []
    ) {
        parent::__construct($key, $status);
    }
}
