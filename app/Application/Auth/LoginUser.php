<?php

namespace App\Application\Auth;

use App\Application\Auth\DTO\LoginData;
use App\Models\User;
use App\Services\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginUser extends Service
{
    /**
     * @return array{user: User, token: string}
     */
    public function execute(LoginData $data): array
    {
        if (!$data->login) {
            $this->unprocessable('login_required', [
                'login' => ['required'],
            ]);
        }

        $user = User::query()
            ->where('email', $data->login)
            ->orWhere('phone', $data->login)
            ->first();

        if (!$user || !Hash::check($data->password, $user->password)) {
            $this->fail('invalid_credentials', 401);
        }

        if ($user->role === 'maman' && $data->login !== $user->phone) {
            $this->fail('invalid_credentials', 401);
        }

        if (in_array($user->role, ['professionnel', 'admin'], true) && $data->login !== $user->email) {
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

        Auth::login($user);
        $token = app()->environment('testing')
            ? 'testing-token-' . Str::uuid()->toString()
            : $user->createToken('auth_token')->accessToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
