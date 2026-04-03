<?php

namespace App\Services;

use App\Exceptions\ApiException;
use Illuminate\Support\Facades\Validator;

abstract class Service
{
    /**
     * Validate a payload and return the validated data.
     *
     * @param array<string, mixed> $data
     * @param array<string, mixed> $rules
     * @param array<string, mixed> $messages
     * @return array<string, mixed>
     */
    protected function validate(array $data, array $rules, array $messages = []): array
    {
        return Validator::make($data, $rules, $messages)->validate();
    }

    /**
     * Throw a JSON HTTP exception.
     *
     * @param array<string, mixed> $payload
     */
    protected function fail(string $key, int $status, string $type = 'error', array $context = []): never
    {
        throw new ApiException($type, $key, $status, $context);
    }

    /**
     * Throw a 404-style JSON error.
     */
    protected function notFound(string $key): never
    {
        throw new ApiException('not_found', $key, 404);
    }

    /**
     * Throw a 401-style JSON error.
     */
    protected function unauthorized(string $key = 'unauthenticated'): never
    {
        throw new ApiException('unauthorized', $key, 401);
    }

    /**
     * Throw a 422-style JSON error.
     *
     * @param array<string, mixed> $errors
     */
    protected function unprocessable(string $key, array $errors = []): never
    {
        throw new ApiException('unprocessable', $key, 422, $errors);
    }
}
