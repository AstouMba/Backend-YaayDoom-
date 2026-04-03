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
        return response()->json($consultations->map(fn ($consultation) => $consultation->toContractArray())->values());
    }

    /**
     * Affiche une consultation spécifique
     */
    public function show(string $id): JsonResponse
    {
        $consultation = $this->consultationService->get($id);
        return response()->json($consultation->toContractArray());
    }

    /**
     * Crée une nouvelle consultation
     */
    public function store(Request $request): JsonResponse
    {
        $consultation = $this->consultationService->create(ConsultationValidator::store($request->all()));
        return response()->json($consultation->toContractArray(), 201);
    }

    /**
     * Met à jour une consultation
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $consultation = $this->consultationService->update($id, ConsultationValidator::update($request->all()));
        return response()->json($consultation->toContractArray());
    }

    /**
     * Supprime une consultation
     */
    public function destroy(string $id): JsonResponse
    {
        $this->consultationService->delete($id);

        return response()->json(['message' => 'Consultation deleted successfully']);
    }
}
