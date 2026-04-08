<?php

namespace App\Features\Admin;

use Illuminate\Support\Facades\Validator;

class AdminValidator
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
        ])->validate();
    }

    /**
     * @return array<string, mixed>
     */
    public static function update(array $data, string $adminId): array
    {
        return Validator::make($data, [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $adminId,
            'phone' => 'sometimes|nullable|string|max:30|unique:users,phone,' . $adminId,
            'password' => 'sometimes|string|min:8',
        ])->validate();
    }

    /**
     * @return array<string, mixed>
     */
    public static function role(array $data): array
    {
        return Validator::make($data, [
            'role' => 'required|string|in:maman,professionnel,admin',
        ])->validate();
    }

    /**
     * @return array<string, mixed>
     */
    public static function status(array $data): array
    {
        return Validator::make($data, [
            'status' => 'required|string|in:actif,inactif',
        ])->validate();
    }

    /**
     * @return array<string, mixed>
     */
    public static function reject(array $data): array
    {
        return Validator::make($data, [
            'motif' => 'required|string|max:500',
        ])->validate();
    }

    /**
     * @return array<string, mixed>
     */
    public static function approve(array $data): array
    {
        return Validator::make($data, [
            'motif' => 'required|string|max:500',
        ])->validate();
    }
}
