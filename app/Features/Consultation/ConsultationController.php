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
        $consultations = $this->consultationService->getAll(request()->user());
        return response()->json($consultations->map(fn ($consultation) => ConsultationPresenter::contract($consultation))->values());
    }

    /**
     * Affiche une consultation spécifique
     */
    public function show(string $id): JsonResponse
    {
        $consultation = $this->consultationService->get($id, request()->user());
        return response()->json(ConsultationPresenter::contract($consultation));
    }

    /**
     * Crée une nouvelle consultation
     */
    public function store(Request $request): JsonResponse
    {
        $data = ConsultationValidator::store($request->all());

        if (request()->user()?->role === 'professionnel') {
            $data['professionnel_id'] = request()->user()->id;
        }

        $consultation = $this->consultationService->create($data);
        return response()->json(ConsultationPresenter::contract($consultation), 201);
    }

    /**
     * Met à jour une consultation
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $consultation = $this->consultationService->update($id, ConsultationValidator::update($request->all()), request()->user());
        return response()->json(ConsultationPresenter::contract($consultation));
    }

    /**
     * Supprime une consultation
     */
    public function destroy(string $id): JsonResponse
    {
        $this->consultationService->delete($id, request()->user());

        return response()->json(['message' => 'Consultation deleted successfully']);
    }
}
