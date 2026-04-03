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
        return response()->json($scan);
    }

    /**
     * Crée un nouveau scan
     */
    public function store(Request $request): JsonResponse
    {
        $scan = $this->scanService->create(ScanValidator::store($request->all()));
        return response()->json($scan, 201);
    }

    /**
     * Met à jour un scan
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $scan = $this->scanService->update($id, ScanValidator::update($request->all()));
        return response()->json($scan);
    }

    /**
     * Supprime un scan
     */
    public function destroy(string $id): JsonResponse
    {
        $this->scanService->delete($id);

        return response()->json(['message' => 'Scan deleted successfully']);
    }

    /**
     * Résout un code QR (scanner / upload) vers un dossier patient
     */
    public function resolve(Request $request): JsonResponse
    {
        $result = $this->scanService->resolveFromQrCode(
            ScanValidator::resolve($request->all())['qr_code'],
            Auth::id()
        );

        return response()->json($result);
    }
}
