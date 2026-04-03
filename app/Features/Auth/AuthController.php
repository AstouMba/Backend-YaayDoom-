<?php

namespace App\Features\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * Inscrire un nouvel utilisateur
     */
    public function register(Request $request): JsonResponse
    {
        $user = $this->authService->register(AuthValidator::register($request->all()));

        return response()->json([
            'success' => true,
            'message' => $user->role === 'professionnel'
                ? 'Compte créé. Votre compte est en attente de validation.'
                : 'Compte créé avec succès.',
            'user' => $user->toContractArray(),
        ], 201);
    }

    /**
     * Connecter un utilisateur
     */
    public function login(Request $request): JsonResponse
    {
        $result = $this->authService->login(AuthValidator::login($request->all()));

        return response()->json([
            'token' => $result['token'],
            'user' => $result['user']->toContractArray(),
        ]);
    }

    /**
     * Déconnecter un utilisateur
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json(['success' => true]);
    }

    /**
     * Récupérer l'utilisateur connecté
     */
    public function me(): JsonResponse
    {
        $user = $this->authService->me();

        return response()->json($user?->toContractArray());
    }

    /**
     * Mettre à jour le profil utilisateur connecté
     */
    public function updateMe(Request $request): JsonResponse
    {
        $user = $this->authService->updateMe(
            AuthValidator::updateMe($request->all(), (string) $request->user()->id),
            $request->user()
        );

        return response()->json($user?->toContractArray());
    }

    /**
     * Changer le mot de passe utilisateur connecté
     */
    public function changePassword(Request $request): JsonResponse
    {
        $this->authService->changePassword(
            AuthValidator::changePassword($request->all()),
            $request->user()
        );

        return response()->json([
            'success' => true,
            'message' => 'Mot de passe mis à jour avec succès.',
        ]);
    }
}
