<?php

namespace App\Features\RendezVous;

use App\Http\Controllers\Controller;
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
        $rendezVous = $this->rendezVousService->getAll(request()->user());
        return response()->json($rendezVous->map(function ($rendezVous) {
            $rendezVous->loadMissing('professionnel');
            return RendezVousPresenter::contract($rendezVous);
        })->values());
    }

    /**
     * Affiche un rendez-vous spécifique
     */
    public function show(string $id): JsonResponse
    {
        $rendezVous = $this->rendezVousService->get($id, request()->user());
        $rendezVous->loadMissing('professionnel');

        return response()->json(RendezVousPresenter::contract($rendezVous));
    }

    /**
     * Crée un nouveau rendez-vous
     */
    public function store(Request $request): JsonResponse
    {
        $data = RendezVousValidator::store($request->all());

        if (request()->user()?->role === 'professionnel') {
            $data['professionnel_id'] = request()->user()->id;
        }

        $rendezVous = $this->rendezVousService->create($data);
        $rendezVous->loadMissing('professionnel');
        return response()->json(RendezVousPresenter::contract($rendezVous), 201);
    }

    /**
     * Met à jour un rendez-vous
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $rendezVous = $this->rendezVousService->update($id, RendezVousValidator::update($request->all()), request()->user());
        $rendezVous->loadMissing('professionnel');

        return response()->json(RendezVousPresenter::contract($rendezVous));
    }

    /**
     * Supprime un rendez-vous
     */
    public function destroy(string $id): JsonResponse
    {
        $this->rendezVousService->delete($id, request()->user());

        return response()->json(['message' => 'Rendez-vous deleted successfully']);
    }
}
