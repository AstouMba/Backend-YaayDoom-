<?php

namespace App\Features\Carte;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarteController extends Controller
{
    public function __construct(
        private CarteService $carteService
    ) {}

    /**
     * Liste toutes les cartes
     */
    public function index(): JsonResponse
    {
        $cartes = $this->carteService->getAll();
        return response()->json($cartes);
    }

    /**
     * Affiche une carte spécifique
     */
    public function show(string $id): JsonResponse
    {
        $carte = $this->carteService->get($id);
        
        if (!$carte) {
            return response()->json(['message' => 'Carte not found'], 404);
        }
        
        return response()->json($carte);
    }

    /**
     * Crée une nouvelle carte
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'maman_id' => 'required|exists:users,id',
            'numero_carte' => 'required|string|unique:cartes',
            'date_emission' => 'required|date',
            'date_expiration' => 'sometimes|date',
            'statut' => 'sometimes|string|in:active,inactive,expiree',
        ]);

        $carte = $this->carteService->create($validated);
        return response()->json($carte, 201);
    }

    /**
     * Met à jour une carte
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'maman_id' => 'sometimes|exists:users,id',
            'numero_carte' => 'sometimes|string|unique:cartes,numero_carte,' . $id,
            'date_emission' => 'sometimes|date',
            'date_expiration' => 'sometimes|date',
            'statut' => 'sometimes|string|in:active,inactive,expiree',
        ]);

        $carte = $this->carteService->update($id, $validated);
        
        if (!$carte) {
            return response()->json(['message' => 'Carte not found'], 404);
        }
        
        return response()->json($carte);
    }

    /**
     * Supprime une carte
     */
    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->carteService->delete($id);
        
        if (!$deleted) {
            return response()->json(['message' => 'Carte not found'], 404);
        }
        
        return response()->json(['message' => 'Carte deleted successfully']);
    }
}
