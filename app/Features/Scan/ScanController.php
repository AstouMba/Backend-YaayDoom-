<?php

namespace App\Features\Scan;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScanController extends Controller
{
    public function __construct(
        private ScanService $scanService
    ) {}

    /**
     * Liste tous les scans
     */
    public function index(): JsonResponse
    {
        $scans = $this->scanService->getAll();
        return response()->json($scans);
    }

    /**
     * Affiche un scan spécifique
     */
    public function show(string $id): JsonResponse
    {
        $scan = $this->scanService->get($id);
        
        if (!$scan) {
            return response()->json(['message' => 'Scan not found'], 404);
        }
        
        return response()->json($scan);
    }

    /**
     * Crée un nouveau scan
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'bebe_id' => 'required|exists:bebes,id',
            'type_scan' => 'required|string',
            'date_scan' => 'required|date',
            'resultat' => 'sometimes|string',
            'notes' => 'sometimes|string',
        ]);

        $scan = $this->scanService->create($validated);
        return response()->json($scan, 201);
    }

    /**
     * Met à jour un scan
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'bebe_id' => 'sometimes|exists:bebes,id',
            'type_scan' => 'sometimes|string',
            'date_scan' => 'sometimes|date',
            'resultat' => 'sometimes|string',
            'notes' => 'sometimes|string',
        ]);

        $scan = $this->scanService->update($id, $validated);
        
        if (!$scan) {
            return response()->json(['message' => 'Scan not found'], 404);
        }
        
        return response()->json($scan);
    }

    /**
     * Supprime un scan
     */
    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->scanService->delete($id);
        
        if (!$deleted) {
            return response()->json(['message' => 'Scan not found'], 404);
        }
        
        return response()->json(['message' => 'Scan deleted successfully']);
    }

    /**
     * Résout un code QR (scanner / upload) vers un dossier patient
     */
    public function resolve(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'qr_code' => 'required|string|min:1|max:255',
        ]);

        $result = $this->scanService->resolveFromQrCode(
            $validated['qr_code'],
            Auth::id()
        );

        if (!$result) {
            return response()->json(['message' => 'Patient non trouvé'], 404);
        }

        return response()->json($result);
    }
}
