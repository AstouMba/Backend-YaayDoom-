<?php

namespace App\Features\Maman;

use Illuminate\Support\Facades\Validator;

class MamanValidator
{
    /**
     * @return array<string, mixed>
     */
    public static function store(array $data): array
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30|unique:users,phone',
            'password' => 'required|string|min:8',
            'status' => 'sometimes|string|in:actif,inactif',
        ])->validate();
    }

    /**
     * @return array<string, mixed>
     */
    public static function update(array $data, string $mamanId): array
    {
        return Validator::make($data, [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $mamanId,
            'phone' => 'sometimes|nullable|string|max:30|unique:users,phone,' . $mamanId,
            'password' => 'sometimes|string|min:8',
            'status' => 'sometimes|string|in:actif,inactif',
        ])->validate();
    }
}
