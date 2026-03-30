<?php

namespace App\Features\Vaccination;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VaccinationController extends Controller
{
    public function __construct(
        private VaccinationService $vaccinationService
    ) {}

    /**
     * Liste toutes les vaccinations
     */
    public function index(): JsonResponse
    {
        $vaccinations = $this->vaccinationService->getAll();
        return response()->json($vaccinations);
    }

    /**
     * Affiche une vaccination spécifique
     */
    public function show(string $id): JsonResponse
    {
        $vaccination = $this->vaccinationService->get($id);
        
        if (!$vaccination) {
            return response()->json(['message' => 'Vaccination not found'], 404);
        }
        
        return response()->json($vaccination);
    }

    /**
     * Crée une nouvelle vaccination
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'bebe_id' => 'required|exists:bebes,id',
            'nom_vaccin' => 'required|string',
            'date_vaccination' => 'required|date',
            'prochaine_dose' => 'sometimes|date',
            'notes' => 'sometimes|string',
        ]);

        $vaccination = $this->vaccinationService->create($validated);
        return response()->json($vaccination, 201);
    }

    /**
     * Met à jour une vaccination
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'bebe_id' => 'sometimes|exists:bebes,id',
            'nom_vaccin' => 'sometimes|string',
            'date_vaccination' => 'sometimes|date',
            'prochaine_dose' => 'sometimes|date',
            'notes' => 'sometimes|string',
        ]);

        $vaccination = $this->vaccinationService->update($id, $validated);
        
        if (!$vaccination) {
            return response()->json(['message' => 'Vaccination not found'], 404);
        }
        
        return response()->json($vaccination);
    }

    /**
     * Supprime une vaccination
     */
    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->vaccinationService->delete($id);
        
        if (!$deleted) {
            return response()->json(['message' => 'Vaccination not found'], 404);
        }
        
        return response()->json(['message' => 'Vaccination deleted successfully']);
    }
}
