<?php

namespace App\Features\Maman;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MamanController extends Controller
{
    public function __construct(
        private MamanService $mamanService
    ) {}

    /**
     * Liste toutes les mamans
     */
    public function index(): JsonResponse
    {
        $mamans = $this->mamanService->getAll();

        return response()->json($mamans);
    }

    /**
     * Affiche une maman spécifique
     */
    public function show(string $id): JsonResponse
    {
        $maman = $this->mamanService->get($id);

        if (!$maman) {
            return response()->json(['message' => 'Maman not found'], 404);
        }

        return response()->json($maman);
    }

    /**
     * Crée une nouvelle maman
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30|unique:users,phone',
            'password' => 'required|string|min:8',
            'status' => 'sometimes|string|in:actif,inactif',
        ]);

        $maman = $this->mamanService->create($validated);

        return response()->json($maman, 201);
    }

    /**
     * Met à jour une maman
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'phone' => 'sometimes|nullable|string|max:30|unique:users,phone,' . $id,
            'password' => 'sometimes|string|min:8',
            'status' => 'sometimes|string|in:actif,inactif',
        ]);

        $maman = $this->mamanService->update($id, $validated);

        if (!$maman) {
            return response()->json(['message' => 'Maman not found'], 404);
        }

        return response()->json($maman);
    }

    /**
     * Supprime une maman
     */
    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->mamanService->delete($id);

        if (!$deleted) {
            return response()->json(['message' => 'Maman not found'], 404);
        }

        return response()->json(['message' => 'Maman deleted successfully']);
    }
}
