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
        $bebes = $this->bebeService->getAll(request()->user());
        return response()->json($bebes->map(fn ($bebe) => BebePresenter::contract($bebe))->values());
    }

    /**
     * Affiche un bébé spécifique
     */
    public function show(string $id): JsonResponse
    {
        $bebe = $this->bebeService->get($id, request()->user());
        return response()->json(BebePresenter::contract($bebe));
    }

    /**
     * Crée un nouveau bébé
     */
    public function store(Request $request): JsonResponse
    {
        $bebe = $this->bebeService->create(BebeValidator::store($request->all()));
        return response()->json(BebePresenter::contract($bebe), 201);
    }

    /**
     * Met à jour un bébé
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $bebe = $this->bebeService->update($id, BebeValidator::update($request->all()));
        return response()->json(BebePresenter::contract($bebe));
    }

    /**
     * Supprime un bébé
     */
    public function destroy(string $id): JsonResponse
    {
        $this->bebeService->delete($id);

        return response()->json(['message' => 'Bebe deleted successfully']);
    }
}
