<?php

namespace App\Features\Professionnel;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfessionnelController extends Controller
{
    public function __construct(
        private ProfessionnelService $professionnelService
    ) {}

    /**
     * Liste tous les professionnels
     */
    public function index(): JsonResponse
    {
        $professionnels = $this->professionnelService->getAll();

        return response()->json($professionnels);
    }

    /**
     * Affiche un professionnel spécifique
     */
    public function show(string $id): JsonResponse
    {
        $professionnel = $this->professionnelService->get($id);

        return response()->json($professionnel);
    }

    /**
     * Crée un nouveau professionnel
     */
    public function store(Request $request): JsonResponse
    {
        $professionnel = $this->professionnelService->create(ProfessionnelValidator::store($request->all()));

        return response()->json($professionnel, 201);
    }

    /**
     * Met à jour un professionnel
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $professionnel = $this->professionnelService->update($id, ProfessionnelValidator::update($request->all(), $id));

        return response()->json($professionnel);
    }

    /**
     * Supprime un professionnel
     */
    public function destroy(string $id): JsonResponse
    {
        $this->professionnelService->delete($id);

        return response()->json(['message' => 'Professionnel deleted successfully']);
    }
}
