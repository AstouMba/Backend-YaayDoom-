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
        $vaccinations = $this->vaccinationService->getAll(request()->user());
        return response()->json($vaccinations->map(function ($vaccination) {
            $vaccination->loadMissing('bebe');
            return VaccinationPresenter::contract($vaccination);
        })->values());
    }

    /**
     * Affiche une vaccination spécifique
     */
    public function show(string $id): JsonResponse
    {
        $vaccination = $this->vaccinationService->get($id, request()->user());
        $vaccination->loadMissing('bebe');

        return response()->json(VaccinationPresenter::contract($vaccination));
    }

    /**
     * Crée une nouvelle vaccination
     */
    public function store(Request $request): JsonResponse
    {
        $data = VaccinationValidator::store($request->all());

        if (request()->user()?->role === 'professionnel') {
            $data['professionnel_id'] = request()->user()->id;
        }

        $vaccination = $this->vaccinationService->create($data);
        $vaccination->loadMissing('bebe');
        return response()->json(VaccinationPresenter::contract($vaccination), 201);
    }

    /**
     * Met à jour une vaccination
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $vaccination = $this->vaccinationService->update($id, VaccinationValidator::update($request->all()), request()->user());
        $vaccination->loadMissing('bebe');

        return response()->json(VaccinationPresenter::contract($vaccination));
    }

    /**
     * Supprime une vaccination
     */
    public function destroy(string $id): JsonResponse
    {
        $this->vaccinationService->delete($id, request()->user());

        return response()->json(['message' => 'Vaccination deleted successfully']);
    }
}
