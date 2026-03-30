<?php

namespace App\Features\Professionnel;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfessionnelController extends Controller
{
    public function __construct(
        private ProfessionnelService $professionnelService
    ) {}

    /**
     * Liste tous les professionnels
     */
    public function index(): JsonResponse
    {
        $professionnels = $this->professionnelService->getAll();

        return response()->json($professionnels);
    }

    /**
     * Affiche un professionnel spécifique
     */
    public function show(string $id): JsonResponse
    {
        $professionnel = $this->professionnelService->get($id);

        if (!$professionnel) {
            return response()->json(['message' => 'Professionnel not found'], 404);
        }

        return response()->json($professionnel);
    }

    /**
     * Crée un nouveau professionnel
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30|unique:users,phone',
            'password' => 'required|string|min:8',
            'status' => 'sometimes|string|in:actif,inactif',
            'is_validated' => 'sometimes|boolean',
            'specialite' => 'required|string|max:255',
            'matricule' => 'required|string|max:255',
            'centre_de_sante' => 'required|string|max:255',
        ]);

        $professionnel = $this->professionnelService->create($validated);

        return response()->json($professionnel, 201);
    }

    /**
     * Met à jour un professionnel
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'phone' => 'sometimes|nullable|string|max:30|unique:users,phone,' . $id,
            'password' => 'sometimes|string|min:8',
            'status' => 'sometimes|string|in:actif,inactif',
            'is_validated' => 'sometimes|boolean',
            'specialite' => 'sometimes|nullable|string|max:255',
            'matricule' => 'sometimes|nullable|string|max:255',
            'centre_de_sante' => 'sometimes|nullable|string|max:255',
        ]);

        $professionnel = $this->professionnelService->update($id, $validated);

        if (!$professionnel) {
            return response()->json(['message' => 'Professionnel not found'], 404);
        }

        return response()->json($professionnel);
    }

    /**
     * Supprime un professionnel
     */
    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->professionnelService->delete($id);

        if (!$deleted) {
            return response()->json(['message' => 'Professionnel not found'], 404);
        }

        return response()->json(['message' => 'Professionnel deleted successfully']);
    }
}
