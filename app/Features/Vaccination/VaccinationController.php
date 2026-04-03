<?php

namespace App\Features\Vaccination;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
        return response()->json($vaccinations->map(function ($vaccination) {
            $vaccination->loadMissing('bebe');
            return $vaccination->toContractArray();
        })->values());
    }

    /**
     * Affiche une vaccination spécifique
     */
    public function show(string $id): JsonResponse
    {
        $vaccination = $this->vaccinationService->get($id);
        $vaccination->loadMissing('bebe');

        return response()->json($vaccination->toContractArray());
    }

    /**
     * Crée une nouvelle vaccination
     */
    public function store(Request $request): JsonResponse
    {
        $vaccination = $this->vaccinationService->create(array_merge(VaccinationValidator::store($request->all()), [
            'professionnel_id' => Auth::id(),
        ]));
        $vaccination->loadMissing('bebe');
        return response()->json($vaccination->toContractArray(), 201);
    }

    /**
     * Met à jour une vaccination
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $vaccination = $this->vaccinationService->update($id, VaccinationValidator::update($request->all()));
        $vaccination->loadMissing('bebe');

        return response()->json($vaccination->toContractArray());
    }

    /**
     * Supprime une vaccination
     */
    public function destroy(string $id): JsonResponse
    {
        $this->vaccinationService->delete($id);

        return response()->json(['message' => 'Vaccination deleted successfully']);
    }
}
