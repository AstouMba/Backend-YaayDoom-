<?php

namespace App\Features\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    /**
     * Liste tous les utilisateurs
     */
    public function index(): JsonResponse
    {
        $users = $this->userService->getAll();

        return response()->json($users);
    }

    /**
     * Affiche un utilisateur spécifique
     */
    public function show(string $id): JsonResponse
    {
        $user = $this->userService->get($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    /**
     * Crée un nouvel utilisateur
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30|unique:users,phone',
            'password' => 'required|string|min:8',
            'role' => 'sometimes|string|in:user,admin,maman,professionnel',
            'status' => 'sometimes|string|in:actif,inactif',
            'is_validated' => 'sometimes|boolean',
            'specialite' => 'nullable|string|max:255',
            'matricule' => 'nullable|string|max:255',
            'centre_de_sante' => 'nullable|string|max:255',
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $user = $this->userService->create($validated);

        return response()->json($user, 201);
    }

    /**
     * Met à jour un utilisateur
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'phone' => 'sometimes|nullable|string|max:30|unique:users,phone,' . $id,
            'password' => 'sometimes|string|min:8',
            'role' => 'sometimes|string|in:user,admin,maman,professionnel',
            'status' => 'sometimes|string|in:actif,inactif',
            'is_validated' => 'sometimes|boolean',
            'specialite' => 'sometimes|nullable|string|max:255',
            'matricule' => 'sometimes|nullable|string|max:255',
            'centre_de_sante' => 'sometimes|nullable|string|max:255',
            'rejection_reason' => 'sometimes|nullable|string|max:500',
        ]);

        $user = $this->userService->update($id, $validated);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    /**
     * Supprime un utilisateur
     */
    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->userService->delete($id);

        if (!$deleted) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['message' => 'User deleted successfully']);
    }
}
