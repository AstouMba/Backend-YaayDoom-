<?php

namespace App\Features\Consultation;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function __construct(
        private ConsultationService $consultationService
    ) {}

    /**
     * Liste toutes les consultations
     */
    public function index(): JsonResponse
    {
        $consultations = $this->consultationService->getAll();
        return response()->json($consultations);
    }

    /**
     * Affiche une consultation spécifique
     */
    public function show(string $id): JsonResponse
    {
        $consultation = $this->consultationService->get($id);
        
        if (!$consultation) {
            return response()->json(['message' => 'Consultation not found'], 404);
        }
        
        return response()->json($consultation);
    }

    /**
     * Crée une nouvelle consultation
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'maman_id' => 'required|exists:users,id',
            'professionnel_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'heure' => 'required',
            'type' => 'required|string',
            'notes' => 'sometimes|string',
        ]);

        $consultation = $this->consultationService->create($validated);
        return response()->json($consultation, 201);
    }

    /**
     * Met à jour une consultation
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'maman_id' => 'sometimes|exists:users,id',
            'professionnel_id' => 'sometimes|exists:users,id',
            'date' => 'sometimes|date',
            'heure' => 'sometimes',
            'type' => 'sometimes|string',
            'notes' => 'sometimes|string',
        ]);

        $consultation = $this->consultationService->update($id, $validated);
        
        if (!$consultation) {
            return response()->json(['message' => 'Consultation not found'], 404);
        }
        
        return response()->json($consultation);
    }

    /**
     * Supprime une consultation
     */
    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->consultationService->delete($id);
        
        if (!$deleted) {
            return response()->json(['message' => 'Consultation not found'], 404);
        }
        
        return response()->json(['message' => 'Consultation deleted successfully']);
    }
}
