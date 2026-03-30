<?php

namespace App\Features\Grossesse;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GrossesseController extends Controller
{
    public function __construct(
        private GrossesseService $grossesseService
    ) {}

    /**
     * Liste toutes les grossesses
     */
    public function index(): JsonResponse
    {
        $grossesses = $this->grossesseService->getAll();
        return response()->json($grossesses);
    }

    /**
     * Affiche une grossesse spécifique
     */
    public function show(string $id): JsonResponse
    {
        $grossesse = $this->grossesseService->get($id);
        
        if (!$grossesse) {
            return response()->json(['message' => 'Grossesse not found'], 404);
        }
        
        return response()->json($grossesse);
    }

    /**
     * Crée une nouvelle grossesse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'maman_id' => 'required|exists:users,id',
            'date_debut' => 'required|date',
            'date_fin_prevue' => 'sometimes|date',
            'statut' => 'sometimes|string|in:en_attente,en_cours,terminee,annulee',
            'notes' => 'sometimes|string',
        ]);

        // Le front envoie parfois un statut initial "en_cours", mais le cycle métier
        // attendu est une déclaration en attente de validation.
        $validated['statut'] = 'en_attente';

        $grossesse = $this->grossesseService->create($validated);
        return response()->json($grossesse, 201);
    }

    /**
     * Met à jour une grossesse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'maman_id' => 'sometimes|exists:users,id',
            'date_debut' => 'sometimes|date',
            'date_fin_prevue' => 'sometimes|date',
            'statut' => 'sometimes|string|in:en_attente,en_cours,terminee,annulee',
            'notes' => 'sometimes|string',
        ]);

        $grossesse = $this->grossesseService->update($id, $validated);
        
        if (!$grossesse) {
            return response()->json(['message' => 'Grossesse not found'], 404);
        }
        
        return response()->json($grossesse);
    }

    /**
     * Supprime une grossesse
     */
    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->grossesseService->delete($id);
        
        if (!$deleted) {
            return response()->json(['message' => 'Grossesse not found'], 404);
        }
        
        return response()->json(['message' => 'Grossesse deleted successfully']);
    }
}
