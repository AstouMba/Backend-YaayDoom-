<?php

namespace App\Features\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct(
        private AdminService $adminService
    ) {}

    /**
     * Liste tous les administrateurs
     */
    public function index(): JsonResponse
    {
        $admins = $this->adminService->getAll();

        return response()->json($admins);
    }

    /**
     * Affiche un administrateur spécifique
     */
    public function show(string $id): JsonResponse
    {
        $admin = $this->adminService->get($id);

        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }

        return response()->json($admin);
    }

    /**
     * Crée un nouvel administrateur
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30|unique:users,phone',
            'password' => 'required|string|min:8',
        ]);

        $admin = $this->adminService->create($validated);

        return response()->json($admin, 201);
    }

    /**
     * Met à jour un administrateur
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'phone' => 'sometimes|nullable|string|max:30|unique:users,phone,' . $id,
            'password' => 'sometimes|string|min:8',
        ]);

        $admin = $this->adminService->update($id, $validated);

        if (!$admin) {
            return response()->json(['message' => 'Admin not found'], 404);
        }

        return response()->json($admin);
    }

    /**
     * Supprime un administrateur
     */
    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->adminService->delete($id);

        if (!$deleted) {
            return response()->json(['message' => 'Admin not found'], 404);
        }

        return response()->json(['message' => 'Admin deleted successfully']);
    }

    /**
     * Liste des utilisateurs (écran admin)
     */
    public function users(Request $request): JsonResponse
    {
        $users = $this->adminService->getUsers([
            'role' => $request->query('role'),
            'status' => $request->query('status'),
            'search' => $request->query('search'),
        ]);

        return response()->json($users);
    }

    /**
     * Statistiques dashboard admin
     */
    public function stats(): JsonResponse
    {
        return response()->json($this->adminService->getStats());
    }

    /**
     * Professionnels en attente
     */
    public function pendingProfessionnels(): JsonResponse
    {
        return response()->json($this->adminService->getPendingProfessionnels());
    }

    /**
     * Valider un professionnel
     */
    public function approveProfessionnel(User $user): JsonResponse
    {
        if ($user->role !== 'professionnel') {
            return response()->json(['message' => 'Cet utilisateur n\'est pas un professionnel'], 422);
        }

        $updated = $this->adminService->approveProfessionnel($user);

        return response()->json([
            'message' => 'Professionnel approuvé avec succès.',
            'user' => $updated,
        ]);
    }

    /**
     * Rejeter un professionnel
     */
    public function rejectProfessionnel(Request $request, User $user): JsonResponse
    {
        if ($user->role !== 'professionnel') {
            return response()->json(['message' => 'Cet utilisateur n\'est pas un professionnel'], 422);
        }

        $validated = $request->validate([
            'motif' => 'nullable|string|max:500',
        ]);

        $updated = $this->adminService->rejectProfessionnel($user, $validated['motif'] ?? null);

        return response()->json([
            'message' => 'Professionnel rejeté.',
            'user' => $updated,
        ]);
    }

    /**
     * Changer le rôle d'un utilisateur
     */
    public function updateUserRole(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'role' => 'required|string|in:maman,professionnel,admin',
        ]);

        $updated = $this->adminService->updateRole($user, $validated['role']);

        return response()->json([
            'message' => 'Rôle utilisateur mis à jour.',
            'user' => $updated,
        ]);
    }

    /**
     * Changer le statut d'un utilisateur
     */
    public function updateUserStatus(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:actif,inactif',
        ]);

        $updated = $this->adminService->updateStatus($user, $validated['status']);

        return response()->json([
            'message' => 'Statut utilisateur mis à jour.',
            'user' => $updated,
        ]);
    }
}
