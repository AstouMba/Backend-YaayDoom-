<?php

namespace App\Features\RendezVous;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RendezVousController extends Controller
{
    public function __construct(
        private RendezVousService $rendezVousService
    ) {}

    /**
     * Liste tous les rendez-vous
     */
    public function index(): JsonResponse
    {
        $rendezVous = $this->rendezVousService->getAll();
        return response()->json($rendezVous);
    }

    /**
     * Affiche un rendez-vous spécifique
     */
    public function show(string $id): JsonResponse
    {
        $rendezVous = $this->rendezVousService->get($id);
        
        if (!$rendezVous) {
            return response()->json(['message' => 'Rendez-vous not found'], 404);
        }
        
        return response()->json($rendezVous);
    }

    /**
     * Crée un nouveau rendez-vous
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'maman_id' => 'required|exists:users,id',
            'professionnel_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'heure' => 'required',
            'motif' => 'required|string',
            'statut' => 'sometimes|string|in:en_attente,confirme,annule',
        ]);

        $rendezVous = $this->rendezVousService->create($validated);
        return response()->json($rendezVous, 201);
    }

    /**
     * Met à jour un rendez-vous
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'maman_id' => 'sometimes|exists:users,id',
            'professionnel_id' => 'sometimes|exists:users,id',
            'date' => 'sometimes|date',
            'heure' => 'sometimes',
            'motif' => 'sometimes|string',
            'statut' => 'sometimes|string|in:en_attente,confirme,annule',
        ]);

        $rendezVous = $this->rendezVousService->update($id, $validated);
        
        if (!$rendezVous) {
            return response()->json(['message' => 'Rendez-vous not found'], 404);
        }
        
        return response()->json($rendezVous);
    }

    /**
     * Supprime un rendez-vous
     */
    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->rendezVousService->delete($id);
        
        if (!$deleted) {
            return response()->json(['message' => 'Rendez-vous not found'], 404);
        }
        
        return response()->json(['message' => 'Rendez-vous deleted successfully']);
    }
}
