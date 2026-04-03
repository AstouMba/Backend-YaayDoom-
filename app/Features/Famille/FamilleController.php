<?php

namespace App\Features\Famille;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class FamilleController extends Controller
{
    public function __construct(
        private FamilleService $familleService
    ) {}

    public function show(string $uuid): JsonResponse
    {
        $family = $this->familleService->get($uuid);

        return response()->json($family);
    }

    public function maman(string $uuid): JsonResponse
    {
        $family = $this->familleService->getMaman($uuid);

        return response()->json($family);
    }

    public function bebe(string $uuid, string $bebeUuid): JsonResponse
    {
        $family = $this->familleService->getBebe($uuid, $bebeUuid);

        return response()->json($family);
    }
}
