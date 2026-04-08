<?php

namespace App\Features\Admin;

use App\Features\User\UserPresenter;
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

        return response()->json($admins->map(fn (User $admin) => UserPresenter::admin($admin))->values());
    }

    /**
     * Affiche un administrateur spécifique
     */
    public function show(string $id): JsonResponse
    {
        $admin = $this->adminService->get($id);

        return response()->json(UserPresenter::admin($admin));
    }

    /**
     * Crée un nouvel administrateur
     */
    public function store(Request $request): JsonResponse
    {
        $admin = $this->adminService->create(AdminValidator::store($request->all()));

        return response()->json(UserPresenter::admin($admin), 201);
    }

    /**
     * Met à jour un administrateur
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $admin = $this->adminService->update($id, AdminValidator::update($request->all(), $id));

        return response()->json(UserPresenter::admin($admin));
    }

    /**
     * Supprime un administrateur
     */
    public function destroy(string $id): JsonResponse
    {
        $this->adminService->delete($id);

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
            'page' => $request->query('page', 1),
            'per_page' => $request->query('per_page', 10),
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
    public function approveProfessionnel(Request $request, User $user): JsonResponse
    {
        if ($user->role !== 'professionnel') {
            return response()->json(['message' => 'Cet utilisateur n\'est pas un professionnel'], 422);
        }

        $validated = AdminValidator::approve($request->all());
        $updated = $this->adminService->approveProfessionnel($user, $validated['motif'], $request->user());

        return response()->json([
            'success' => true,
            'message' => 'Professionnel approuvé',
            'motif' => $validated['motif'],
            'professionnel' => UserPresenter::admin($updated),
        ]);
    }

    /**
     * Rejeter un professionnel
     */
    public function rejectProfessionnel(Request $request, User $user): JsonResponse
    {
        $validated = AdminValidator::reject($request->all());
        $updated = $this->adminService->rejectProfessionnel($user, $validated['motif'], $request->user());

        return response()->json([
            'success' => true,
            'message' => 'Demande rejetée',
            'motif' => $validated['motif'],
            'professionnel' => UserPresenter::admin($updated),
        ]);
    }

    /**
     * Changer le rôle d'un utilisateur
     */
    public function updateUserRole(Request $request, User $user): JsonResponse
    {
        $validated = AdminValidator::role($request->all());
        $updated = $this->adminService->updateRole($user, $validated['role']);

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Changer le statut d'un utilisateur
     */
    public function updateUserStatus(Request $request, User $user): JsonResponse
    {
        $validated = AdminValidator::status($request->all());
        $updated = $this->adminService->updateStatus($user, $validated['status']);

        return response()->json([
            'success' => true,
        ]);
    }
}
