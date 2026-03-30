<?php

namespace App\Features\Bebe;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BebeController extends Controller
{
    public function __construct(
        private BebeService $bebeService
    ) {}

    /**
     * Liste tous les bébés
     */
    public function index(): JsonResponse
    {
        $bebes = $this->bebeService->getAll();
        return response()->json($bebes);
    }

    /**
     * Affiche un bébé spécifique
     */
    public function show(string $id): JsonResponse
    {
        $bebe = $this->bebeService->get($id);
        
        if (!$bebe) {
            return response()->json(['message' => 'Bebe not found'], 404);
        }
        
        return response()->json($bebe);
    }

    /**
     * Crée un nouveau bébé
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'maman_id' => 'required|exists:users,id',
            'nom' => 'required|string|max:255',
            'date_naissance' => 'required|date',
            'sexe' => 'required|string|in:M,F',
            'poids' => 'sometimes|numeric',
            'taille' => 'sometimes|numeric',
            'notes' => 'sometimes|string',
        ]);

        $bebe = $this->bebeService->create($validated);
        return response()->json($bebe, 201);
    }

    /**
     * Met à jour un bébé
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'maman_id' => 'sometimes|exists:users,id',
            'nom' => 'sometimes|string|max:255',
            'date_naissance' => 'sometimes|date',
            'sexe' => 'sometimes|string|in:M,F',
            'poids' => 'sometimes|numeric',
            'taille' => 'sometimes|numeric',
            'notes' => 'sometimes|string',
        ]);

        $bebe = $this->bebeService->update($id, $validated);
        
        if (!$bebe) {
            return response()->json(['message' => 'Bebe not found'], 404);
        }
        
        return response()->json($bebe);
    }

    /**
     * Supprime un bébé
     */
    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->bebeService->delete($id);
        
        if (!$deleted) {
            return response()->json(['message' => 'Bebe not found'], 404);
        }
        
        return response()->json(['message' => 'Bebe deleted successfully']);
    }
}
