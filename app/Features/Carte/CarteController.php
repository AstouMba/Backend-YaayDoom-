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
        return response()->json($carte);
    }

    /**
     * Crée une nouvelle carte
     */
    public function store(Request $request): JsonResponse
    {
        $carte = $this->carteService->create(CarteValidator::store($request->all()));
        return response()->json($carte, 201);
    }

    /**
     * Met à jour une carte
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $carte = $this->carteService->update($id, CarteValidator::update($request->all(), $id));
        return response()->json($carte);
    }

    /**
     * Supprime une carte
     */
    public function destroy(string $id): JsonResponse
    {
        $this->carteService->delete($id);

        return response()->json(['message' => 'Carte deleted successfully']);
    }
}
