<?php

namespace App\Application\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Throwable;

class IssuePassportToken
{
    public function execute(User $user, string $tokenName = 'auth_token'): string
    {
        try {
            $this->ensurePassportIsReady();

            return $user->createToken($tokenName)->accessToken;
        } catch (Throwable $exception) {
            if (! $this->looksLikePassportSetupProblem($exception)) {
                throw $exception;
            }

            $this->ensurePassportIsReady();

            return $user->createToken($tokenName)->accessToken;
        }
    }

    private function ensurePassportIsReady(): void
    {
        Artisan::call('passport:ensure');
    }

    private function looksLikePassportSetupProblem(Throwable $exception): bool
    {
        $message = $exception->getMessage();

        return str_contains($message, 'Personal access client not found')
            || str_contains($message, 'oauth-private.key')
            || str_contains($message, 'oauth-public.key')
            || str_contains($message, 'Encryption keys');
    }
}
