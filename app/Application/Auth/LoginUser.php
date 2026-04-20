<?php

namespace App\Application\Auth;

use App\Application\Auth\DTO\LoginData;
use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginUser extends Service
{
    public function __construct(
        private IssuePassportToken $issuePassportToken,
    ) {}

    /**
     * @return array{user: User, token: string}
     */
    public function execute(LoginData $data): array
    {
        if ($data->email === null && $data->phone === null) {
            $this->unprocessable('login_required', [
                'email' => ['required'],
                'phone' => ['required'],
            ]);
        }

        if ($data->email !== null && $data->phone !== null) {
            $this->unprocessable('login_required', [
                'email' => ['invalid'],
                'phone' => ['invalid'],
            ]);
        }

        if ($data->email !== null) {
            $user = $this->findUserByEmail($data->email);

            if ($user && $user->role === 'maman') {
                $this->fail('invalid_credentials', 401);
            }
        } else {
            $user = $this->findUserByPhone($data->phone ?? '');

            if ($user && $user->role !== 'maman') {
                $this->fail('invalid_credentials', 401);
            }
        }

        if (!$user || !Hash::check($data->password, $user->password)) {
            $this->fail('invalid_credentials', 401);
        }

        if ($data->phone !== null && $user->role === 'maman' && $this->normalizePhone($data->phone) !== $this->normalizePhone($user->phone)) {
            $this->fail('invalid_credentials', 401);
        }

        if ($data->email !== null && in_array($user->role, ['professionnel', 'admin'], true) && $data->email !== $user->email) {
            $this->fail('invalid_credentials', 401);
        }

        if ($user->role === 'professionnel' && !$user->is_validated) {
            $this->fail('professional_pending', 403, 'forbidden', [
                'user' => [
                    'id' => $user->id,
                    'nom' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'isValidated' => (bool) $user->is_validated,
                    'statut' => $user->status,
                ],
            ]);
        }

        if ($user->status === 'inactif') {
            $this->fail('inactive_account', 403, 'forbidden');
        }

        $token = app()->environment('testing')
            ? 'testing-token-' . Str::uuid()->toString()
            : $this->issuePassportToken->execute($user, 'auth_token');

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    private function findUserByEmail(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }

    private function findUserByPhone(string $phone): ?User
    {
        $normalizedPhone = $this->normalizePhone($phone);
        $normalizedPhoneSql = $this->normalizedPhoneSql('phone');

        return User::query()
            ->where('phone', $phone)
            ->orWhereRaw("{$normalizedPhoneSql} = ?", [$normalizedPhone])
            ->first();
    }

    private function normalizePhone(?string $phone): string
    {
        return preg_replace('/\D+/', '', $phone ?? '') ?? '';
    }

    private function normalizedPhoneSql(string $column): string
    {
        foreach ([' ', '-', '(', ')', '.', '+'] as $character) {
            $column = "replace({$column}, '{$character}', '')";
        }

        return $column;
    }
}
