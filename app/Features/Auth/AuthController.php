<?php

namespace App\Features\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30|unique:users,phone',
            'password' => 'required|string|min:8',
            'role' => 'sometimes|string|in:maman,professionnel',
            'specialite' => 'nullable|string|max:255',
            'matricule' => 'nullable|string|max:255',
            'centre_de_sante' => 'nullable|string|max:255',
        ]);

        $role = $validated['role'] ?? 'maman';

        if ($role === 'professionnel') {
            $request->validate([
                'specialite' => 'required|string|max:255',
                'matricule' => 'required|string|max:255',
                'centre_de_sante' => 'required|string|max:255',
            ]);
        }

        $user = $this->authService->register($validated);

        return response()->json([
            'message' => $role === 'professionnel'
                ? 'Compte créé. Validation administrateur en attente.'
                : 'Compte créé avec succès.',
            'user' => $user,
        ], 201);
    }

    /**
     * Connecter un utilisateur
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'password' => 'required|string',
            'login' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        $login = $validated['login'] ?? $validated['email'] ?? null;

        if (!$login) {
            throw ValidationException::withMessages([
                'login' => ['Le champ login ou email est obligatoire.'],
            ]);
        }

        $result = $this->authService->login($login, $validated['password']);

        if (!$result) {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }

        if ($result['user']->role === 'professionnel' && !$result['user']->is_validated) {
            return response()->json([
                'message' => 'Votre compte professionnel est en attente de validation.',
                'user' => $result['user'],
            ], 403);
        }

        if ($result['user']->status === 'inactif') {
            return response()->json([
                'message' => 'Votre compte est inactif. Contactez un administrateur.',
            ], 403);
        }

        return response()->json($result);
    }

    /**
     * Déconnecter un utilisateur
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return response()->json(['message' => 'Déconnecté avec succès']);
    }

    /**
     * Récupérer l'utilisateur connecté
     */
    public function me(): JsonResponse
    {
        $user = $this->authService->me();

        return response()->json($user);
    }

    /**
     * Mettre à jour le profil utilisateur connecté
     */
    public function updateMe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . optional($request->user())->id,
            'phone' => 'sometimes|nullable|string|max:30|unique:users,phone,' . optional($request->user())->id,
            'specialite' => 'sometimes|nullable|string|max:255',
            'centre_de_sante' => 'sometimes|nullable|string|max:255',
        ]);

        $user = $this->authService->updateMe($validated);

        if (!$user) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

        return response()->json($user);
    }

    /**
     * Changer le mot de passe utilisateur connecté
     */
    public function changePassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'currentPassword' => 'required|string',
            'newPassword' => 'required|string|min:8',
        ]);

        $this->authService->changePassword(
            $validated['currentPassword'],
            $validated['newPassword']
        );

        return response()->json([
            'success' => true,
            'message' => 'Mot de passe modifié avec succès.',
        ]);
    }
}
