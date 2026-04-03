<?php

namespace App\Features\Maman;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MamanController extends Controller
{
    public function __construct(
        private MamanService $mamanService
    ) {}

    /**
     * Liste toutes les mamans
     */
    public function index(): JsonResponse
    {
        $mamans = $this->mamanService->getAll();

        return response()->json($mamans);
    }

    /**
     * Affiche une maman spécifique
     */
    public function show(string $id): JsonResponse
    {
        $maman = $this->mamanService->get($id);

        return response()->json($maman);
    }

    /**
     * Crée une nouvelle maman
     */
    public function store(Request $request): JsonResponse
    {
        $maman = $this->mamanService->create(MamanValidator::store($request->all()));

        return response()->json($maman, 201);
    }

    /**
     * Met à jour une maman
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $maman = $this->mamanService->update($id, MamanValidator::update($request->all(), $id));

        return response()->json($maman);
    }

    /**
     * Supprime une maman
     */
    public function destroy(string $id): JsonResponse
    {
        $this->mamanService->delete($id);

        return response()->json(['message' => 'Maman deleted successfully']);
    }
}
