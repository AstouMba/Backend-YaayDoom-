<?php

namespace App\Features\Famille;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FamilleController extends Controller
{
    public function __construct(
        private FamilleService $familleService
    ) {}

    public function show(Request $request, string $uuid): JsonResponse
    {
        $family = $this->familleService->get($uuid, $request->user());

        return response()->json($family);
    }

    public function maman(Request $request, string $uuid): JsonResponse
    {
        $family = $this->familleService->getMaman($uuid, $request->user());

        return response()->json($family);
    }

    public function bebe(Request $request, string $uuid, string $bebeUuid): JsonResponse
    {
        $family = $this->familleService->getBebe($uuid, $bebeUuid, $request->user());

        return response()->json($family);
    }
}
