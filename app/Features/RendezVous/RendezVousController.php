<?php

namespace App\Features\RendezVous;

use App\Http\Controllers\Controller;
use App\Models\Grossesse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RendezVousController extends Controller
{
    public function __construct(
        private RendezVousService $rendezVousService
    ) {}

    /**
     * Liste tous les rendez-vous
     */
    public function index(): JsonResponse
    {
        $rendezVous = $this->rendezVousService->getAll();
        return response()->json($rendezVous->map(function ($rendezVous) {
            $rendezVous->loadMissing('professionnel');
            return $rendezVous->toContractArray();
        })->values());
    }

    /**
     * Affiche un rendez-vous spécifique
     */
    public function show(string $id): JsonResponse
    {
        $rendezVous = $this->rendezVousService->get($id);
        $rendezVous->loadMissing('professionnel');

        return response()->json($rendezVous->toContractArray());
    }

    /**
     * Crée un nouveau rendez-vous
     */
    public function store(Request $request): JsonResponse
    {
        $rendezVous = $this->rendezVousService->create(RendezVousValidator::store($request->all()));
        $rendezVous->loadMissing('professionnel');
        return response()->json($rendezVous->toContractArray(), 201);
    }

    /**
     * Met à jour un rendez-vous
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $rendezVous = $this->rendezVousService->update($id, RendezVousValidator::update($request->all()));
        $rendezVous->loadMissing('professionnel');

        return response()->json($rendezVous->toContractArray());
    }

    /**
     * Supprime un rendez-vous
     */
    public function destroy(string $id): JsonResponse
    {
        $this->rendezVousService->delete($id);

        return response()->json(['message' => 'Rendez-vous deleted successfully']);
    }
}
