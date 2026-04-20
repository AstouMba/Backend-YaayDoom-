<?php

use App\Http\Middleware\RoleMiddleware;
use App\Exceptions\ApiException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(
            at: '*',
            headers: Request::HEADER_X_FORWARDED_FOR
                | Request::HEADER_X_FORWARDED_HOST
                | Request::HEADER_X_FORWARDED_PROTO
                | Request::HEADER_X_FORWARDED_PORT
                | Request::HEADER_X_FORWARDED_PREFIX
        );

        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $messages = [
            'unauthenticated' => 'Non authentifié',
            'user_not_found' => 'Utilisateur introuvable',
            'admin_not_found' => 'Admin introuvable',
            'maman_not_found' => 'Maman introuvable',
            'professionnel_not_found' => 'Professionnel introuvable',
            'grossesse_not_found' => 'Grossesse introuvable',
            'bebe_not_found' => 'Bebe introuvable',
            'consultation_not_found' => 'Consultation introuvable',
            'vaccination_not_found' => 'Vaccination introuvable',
            'rendez_vous_not_found' => 'Rendez-vous introuvable',
            'scan_not_found' => 'Scan introuvable',
            'carte_not_found' => 'Carte introuvable',
            'famille_not_found' => 'Famille introuvable',
            'patient_not_found' => 'Patient non trouvé',
            'invalid_credentials' => 'Identifiants invalides',
            'professional_pending' => 'Votre compte professionnel est en attente de validation.',
            'inactive_account' => 'Votre compte est inactif. Contactez un administrateur.',
            'current_password_incorrect' => 'Le mot de passe actuel est incorrect.',
            'login_required' => 'Le champ login ou email est obligatoire.',
            'qr_code_required' => 'Le champ qr_code est obligatoire.',
            'not_a_professional' => "Cet utilisateur n'est pas un professionnel",
        ];

        $exceptions->render(function (ApiException $e, Request $request) use ($messages) {
            $message = $messages[$e->key] ?? 'Erreur';
            $payload = ['message' => $message];

            if ($e->context !== []) {
                $translatedErrors = [];

                foreach ($e->context as $field => $codes) {
                    $translatedErrors[$field] = array_map(static function (mixed $code): string {
                        return match ($code) {
                            'required' => 'Le champ est requis',
                            'invalid' => 'Valeur invalide',
                            default => (string) $code,
                        };
                    }, is_array($codes) ? $codes : [$codes]);
                }

                $payload = array_merge($payload, $e->context);
                $payload['errors'] = $translatedErrors;
            }

            return response()->json($payload, $e->status);
        });
    })->create();
