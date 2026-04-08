<?php

namespace App\Features\Grossesse;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GrossesseController extends Controller
{
    public function __construct(
        private GrossesseService $grossesseService
    ) {}

    /**
     * Liste toutes les grossesses
     */
    public function index(): JsonResponse
    {
        $grossesses = $this->grossesseService->getAll();
        return response()->json($grossesses->map(function ($grossesse) {
            $grossesse->loadMissing('maman');
            return GrossessePresenter::contract($grossesse);
        })->values());
    }

    /**
     * Affiche une grossesse spécifique
     */
    public function show(string $id): JsonResponse
    {
        $grossesse = $this->grossesseService->get($id);
        $grossesse->loadMissing('maman');

        return response()->json(GrossessePresenter::contract($grossesse));
    }

    /**
     * Crée une nouvelle grossesse
     */
    public function store(Request $request): JsonResponse
    {
        $grossesse = $this->grossesseService->create(GrossesseValidator::store($request->all()), $request->user());
        $grossesse->loadMissing('maman');
        return response()->json(GrossessePresenter::contract($grossesse), 201);
    }

    /**
     * Met à jour une grossesse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $grossesse = $this->grossesseService->update($id, GrossesseValidator::update($request->all()));
        $grossesse->loadMissing('maman');

        return response()->json(GrossessePresenter::contract($grossesse));
    }

    /**
     * Supprime une grossesse
     */
    public function destroy(string $id): JsonResponse
    {
        $this->grossesseService->delete($id);
        
        return response()->json(['message' => 'Grossesse deleted successfully']);
    }
}
