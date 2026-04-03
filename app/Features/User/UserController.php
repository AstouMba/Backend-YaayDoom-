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

        return response()->json($user->toContractArray());
    }

    /**
     * Crée un nouvel utilisateur
     */
    public function store(Request $request): JsonResponse
    {
        $user = $this->userService->create(UserValidator::store($request->all()));

        return response()->json($user->toContractArray(), 201);
    }

    /**
     * Met à jour un utilisateur
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $user = $this->userService->update($id, UserValidator::update($request->all(), $id));

        return response()->json($user->toContractArray());
    }

    /**
     * Supprime un utilisateur
     */
    public function destroy(string $id): JsonResponse
    {
        $this->userService->delete($id);

        return response()->json(['success' => true]);
    }
}
