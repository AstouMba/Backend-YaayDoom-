<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'YaayDoom API',
    description: 'Documentation OpenAPI strictement alignée sur les endpoints utilisés par le frontend, triée par profil'
)]
#[OA\Server(url: 'http://127.0.0.1:8000', description: 'Serveur local')]
#[OA\Tag(name: 'Auth')]
#[OA\Tag(name: 'Admin', description: 'Endpoints de gestion administrateur')]
#[OA\Tag(name: 'Maman', description: 'Endpoints pour le parcours maman')]
#[OA\Tag(name: 'Professionnel', description: 'Endpoints pour le parcours professionnel de santé')]
#[OA\SecurityScheme(
    securityScheme: 'Bearer',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT'
)]
class OpenApiSpec
{
    #[OA\Post(path: '/api/auth/register', tags: ['Auth'], summary: 'Inscription utilisateur', responses: [new OA\Response(response: 201, description: 'Compte créé')])]
    public function authRegister(): void {}

    #[OA\Post(path: '/api/auth/login', tags: ['Auth'], summary: 'Connexion utilisateur', responses: [new OA\Response(response: 200, description: 'Connexion réussie')])]
    public function authLogin(): void {}

    #[OA\Post(path: '/api/auth/logout', tags: ['Auth'], summary: 'Déconnexion', security: [['Bearer' => []]], responses: [new OA\Response(response: 200, description: 'Déconnecté')])]
    public function authLogout(): void {}

    #[OA\Get(path: '/api/auth/me', tags: ['Auth'], summary: 'Utilisateur connecté', security: [['Bearer' => []]], responses: [new OA\Response(response: 200, description: 'Profil')])]
    public function authMe(): void {}

    #[OA\Patch(path: '/api/auth/me', tags: ['Auth'], summary: 'Mise à jour profil connecté', security: [['Bearer' => []]], responses: [new OA\Response(response: 200, description: 'Profil mis à jour')])]
    public function authUpdateMe(): void {}

    #[OA\Post(path: '/api/auth/change-password', tags: ['Auth'], summary: 'Changer mot de passe', security: [['Bearer' => []]], responses: [new OA\Response(response: 200, description: 'Mot de passe modifié')])]
    public function authChangePassword(): void {}

    #[OA\Get(path: '/api/admin/users', tags: ['Admin'], summary: 'Liste utilisateurs (admin)', security: [['Bearer' => []]], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function adminUsers(): void {}

    #[OA\Get(path: '/api/admin/stats', tags: ['Admin'], summary: 'Statistiques admin', security: [['Bearer' => []]], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function adminStats(): void {}

    #[OA\Get(path: '/api/admin/professionnels/pending', tags: ['Admin'], summary: 'Professionnels en attente', security: [['Bearer' => []]], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function adminPendingProfessionnels(): void {}

    #[OA\Post(path: '/api/admin/professionnels/{user}/approve', tags: ['Admin'], summary: 'Approuver professionnel', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'user', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Approuvé')])]
    public function adminApproveProfessionnel(): void {}

    #[OA\Post(path: '/api/admin/professionnels/{user}/reject', tags: ['Admin'], summary: 'Rejeter professionnel', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'user', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Rejeté')])]
    public function adminRejectProfessionnel(): void {}

    #[OA\Patch(path: '/api/admin/users/{user}/role', tags: ['Admin'], summary: 'Changer rôle utilisateur', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'user', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Rôle modifié')])]
    public function adminUpdateUserRole(): void {}

    #[OA\Patch(path: '/api/admin/users/{user}/status', tags: ['Admin'], summary: 'Changer statut utilisateur', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'user', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Statut modifié')])]
    public function adminUpdateUserStatus(): void {}

    #[OA\Get(path: '/api/users/{user}', tags: ['Admin'], summary: 'Afficher user', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'user', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function usersShow(): void {}

    #[OA\Put(path: '/api/users/{user}', tags: ['Admin'], summary: 'Mettre à jour user', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'user', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Modifié')])]
    public function usersUpdate(): void {}

    #[OA\Delete(path: '/api/users/{user}', tags: ['Admin'], summary: 'Supprimer user', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'user', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Supprimé')])]
    public function usersDestroy(): void {}

    #[OA\Get(path: '/api/grossesses', tags: ['Maman', 'Professionnel'], summary: 'Liste grossesses', security: [['Bearer' => []]], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function grossessesIndex(): void {}

    #[OA\Get(path: '/api/grossesses/{grossesse}', tags: ['Maman', 'Professionnel'], summary: 'Afficher grossesse', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'grossesse', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function grossessesShow(): void {}

    #[OA\Post(
        path: '/api/grossesses',
        tags: ['Maman', 'Professionnel'],
        summary: 'Créer grossesse',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['maman_id', 'date_debut'],
                properties: [
                    new OA\Property(property: 'maman_id', type: 'integer', example: 1),
                    new OA\Property(property: 'date_debut', type: 'string', format: 'date', example: '2026-03-28'),
                    new OA\Property(property: 'date_fin_prevue', type: 'string', format: 'date', nullable: true),
                    new OA\Property(property: 'statut', type: 'string', example: 'en_attente'),
                    new OA\Property(property: 'notes', type: 'string', nullable: true),
                ]
            )
        ),
        responses: [new OA\Response(response: 201, description: 'Créé')]
    )]
    public function grossessesStore(): void {}

    #[OA\Put(path: '/api/grossesses/{grossesse}', tags: ['Maman', 'Professionnel'], summary: 'Mettre à jour grossesse', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'grossesse', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Modifié')])]
    public function grossessesUpdatePut(): void {}

    #[OA\Patch(path: '/api/grossesses/{grossesse}', tags: ['Maman', 'Professionnel'], summary: 'Mettre à jour partiellement grossesse', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'grossesse', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Modifié')])]
    public function grossessesUpdatePatch(): void {}

    #[OA\Delete(path: '/api/grossesses/{grossesse}', tags: ['Maman', 'Professionnel'], summary: 'Supprimer grossesse', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'grossesse', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Supprimé')])]
    public function grossessesDestroy(): void {}

    #[OA\Get(path: '/api/bebes', tags: ['Maman', 'Professionnel'], summary: 'Liste bébés', security: [['Bearer' => []]], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function bebesIndex(): void {}

    #[OA\Get(path: '/api/bebes/{bebe}', tags: ['Maman', 'Professionnel'], summary: 'Afficher bébé', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'bebe', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function bebesShow(): void {}

    #[OA\Post(
        path: '/api/bebes',
        tags: ['Maman', 'Professionnel'],
        summary: 'Créer bébé',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['maman_id', 'nom', 'date_naissance', 'sexe'],
                properties: [
                    new OA\Property(property: 'maman_id', type: 'integer', example: 1),
                    new OA\Property(property: 'grossesse_id', type: 'integer', nullable: true),
                    new OA\Property(property: 'nom', type: 'string', example: 'Aminata'),
                    new OA\Property(property: 'date_naissance', type: 'string', format: 'date', example: '2026-03-28'),
                    new OA\Property(property: 'sexe', type: 'string', example: 'F'),
                    new OA\Property(property: 'poids', type: 'number', format: 'float', nullable: true),
                    new OA\Property(property: 'taille', type: 'number', format: 'float', nullable: true),
                    new OA\Property(property: 'notes', type: 'string', nullable: true),
                ]
            )
        ),
        responses: [new OA\Response(response: 201, description: 'Créé')]
    )]
    public function bebesStore(): void {}

    #[OA\Put(path: '/api/bebes/{bebe}', tags: ['Maman', 'Professionnel'], summary: 'Mettre à jour bébé', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'bebe', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Modifié')])]
    public function bebesUpdate(): void {}

    #[OA\Patch(path: '/api/bebes/{bebe}', tags: ['Maman', 'Professionnel'], summary: 'Mettre à jour partiellement bébé', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'bebe', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Modifié')])]
    public function bebesUpdatePatch(): void {}

    #[OA\Delete(path: '/api/bebes/{bebe}', tags: ['Maman', 'Professionnel'], summary: 'Supprimer bébé', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'bebe', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Supprimé')])]
    public function bebesDestroy(): void {}

    #[OA\Get(path: '/api/consultations', tags: ['Professionnel'], summary: 'Liste consultations', security: [['Bearer' => []]], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function consultationsIndex(): void {}

    #[OA\Get(path: '/api/consultations/{consultation}', tags: ['Professionnel'], summary: 'Afficher consultation', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'consultation', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function consultationsShow(): void {}

    #[OA\Post(path: '/api/consultations', tags: ['Professionnel'], summary: 'Créer consultation', security: [['Bearer' => []]], responses: [new OA\Response(response: 201, description: 'Créé')])]
    public function consultationsStore(): void {}

    #[OA\Put(path: '/api/consultations/{consultation}', tags: ['Professionnel'], summary: 'Mettre à jour consultation', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'consultation', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Modifié')])]
    public function consultationsUpdatePut(): void {}

    #[OA\Patch(path: '/api/consultations/{consultation}', tags: ['Professionnel'], summary: 'Mettre à jour consultation', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'consultation', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Modifié')])]
    public function consultationsUpdate(): void {}

    #[OA\Delete(path: '/api/consultations/{consultation}', tags: ['Professionnel'], summary: 'Supprimer consultation', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'consultation', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Supprimé')])]
    public function consultationsDestroy(): void {}

    #[OA\Get(path: '/api/vaccinations', tags: ['Maman', 'Professionnel'], summary: 'Liste vaccinations', security: [['Bearer' => []]], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function vaccinationsIndex(): void {}

    #[OA\Get(path: '/api/vaccinations/{vaccination}', tags: ['Maman', 'Professionnel'], summary: 'Afficher vaccination', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'vaccination', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function vaccinationsShow(): void {}

    #[OA\Post(
        path: '/api/vaccinations',
        tags: ['Maman', 'Professionnel'],
        summary: 'Créer vaccination',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['bebe_id', 'nom_vaccin', 'date_vaccination'],
                properties: [
                    new OA\Property(property: 'bebe_id', type: 'integer', example: 1),
                    new OA\Property(property: 'nom_vaccin', type: 'string', example: 'BCG'),
                    new OA\Property(property: 'date_vaccination', type: 'string', format: 'date', example: '2026-03-28'),
                    new OA\Property(property: 'prochaine_dose', type: 'string', format: 'date', nullable: true),
                    new OA\Property(property: 'notes', type: 'string', nullable: true),
                ]
            )
        ),
        responses: [new OA\Response(response: 201, description: 'Créé')]
    )]
    public function vaccinationsStore(): void {}

    #[OA\Put(path: '/api/vaccinations/{vaccination}', tags: ['Maman', 'Professionnel'], summary: 'Mettre à jour vaccination', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'vaccination', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Modifié')])]
    public function vaccinationsUpdatePut(): void {}

    #[OA\Patch(path: '/api/vaccinations/{vaccination}', tags: ['Maman', 'Professionnel'], summary: 'Mettre à jour vaccination', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'vaccination', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Modifié')])]
    public function vaccinationsUpdate(): void {}

    #[OA\Delete(path: '/api/vaccinations/{vaccination}', tags: ['Maman', 'Professionnel'], summary: 'Supprimer vaccination', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'vaccination', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Supprimé')])]
    public function vaccinationsDestroy(): void {}

    #[OA\Get(path: '/api/rendez-vous', tags: ['Maman'], summary: 'Liste rendez-vous', security: [['Bearer' => []]], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function rendezVousIndex(): void {}

    #[OA\Get(path: '/api/rendez-vous/{rendezVous}', tags: ['Maman'], summary: 'Afficher rendez-vous', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'rendezVous', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function rendezVousShow(): void {}

    #[OA\Post(
        path: '/api/rendez-vous',
        tags: ['Maman'],
        summary: 'Créer rendez-vous',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['maman_id', 'professionnel_id', 'date', 'heure', 'motif'],
                properties: [
                    new OA\Property(property: 'maman_id', type: 'integer', example: 1),
                    new OA\Property(property: 'professionnel_id', type: 'integer', example: 2),
                    new OA\Property(property: 'date', type: 'string', format: 'date', example: '2026-03-28'),
                    new OA\Property(property: 'heure', type: 'string', example: '09:00:00'),
                    new OA\Property(property: 'motif', type: 'string', example: 'Consultation prénatale'),
                    new OA\Property(property: 'statut', type: 'string', example: 'en_attente'),
                ]
            )
        ),
        responses: [new OA\Response(response: 201, description: 'Créé')]
    )]
    public function rendezVousStore(): void {}

    #[OA\Put(path: '/api/rendez-vous/{rendezVous}', tags: ['Maman'], summary: 'Mettre à jour rendez-vous', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'rendezVous', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Modifié')])]
    public function rendezVousUpdatePut(): void {}

    #[OA\Patch(path: '/api/rendez-vous/{rendezVous}', tags: ['Maman'], summary: 'Mettre à jour partiellement rendez-vous', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'rendezVous', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Modifié')])]
    public function rendezVousUpdatePatch(): void {}

    #[OA\Delete(path: '/api/rendez-vous/{rendezVous}', tags: ['Maman'], summary: 'Supprimer rendez-vous', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'rendezVous', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Supprimé')])]
    public function rendezVousDestroy(): void {}

    #[OA\Get(path: '/api/cartes', tags: ['Maman'], summary: 'Liste cartes', security: [['Bearer' => []]], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function cartesIndex(): void {}

    #[OA\Get(path: '/api/cartes/{carte}', tags: ['Maman'], summary: 'Afficher carte', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'carte', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function cartesShow(): void {}

    #[OA\Post(path: '/api/cartes', tags: ['Maman'], summary: 'Créer carte', security: [['Bearer' => []]], responses: [new OA\Response(response: 201, description: 'Créé')])]
    public function cartesStore(): void {}

    #[OA\Put(path: '/api/cartes/{carte}', tags: ['Maman'], summary: 'Mettre à jour carte', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'carte', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Modifié')])]
    public function cartesUpdatePut(): void {}

    #[OA\Patch(path: '/api/cartes/{carte}', tags: ['Maman'], summary: 'Mettre à jour partiellement carte', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'carte', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Modifié')])]
    public function cartesUpdatePatch(): void {}

    #[OA\Delete(path: '/api/cartes/{carte}', tags: ['Maman'], summary: 'Supprimer carte', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'carte', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Supprimé')])]
    public function cartesDestroy(): void {}

    #[OA\Post(
        path: '/api/scans/resolve',
        tags: ['Professionnel'],
        summary: 'Résoudre un code QR en dossier patient',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['qr_code'],
                properties: [new OA\Property(property: 'qr_code', type: 'string', example: 'p-1')]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Patient trouvé'),
            new OA\Response(response: 404, description: 'Patient non trouvé'),
            new OA\Response(response: 422, description: 'Erreur de validation'),
        ]
    )]
    public function scansResolve(): void {}

    #[OA\Get(path: '/api/scans', tags: ['Professionnel'], summary: 'Liste scans', security: [['Bearer' => []]], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function scansIndex(): void {}

    #[OA\Get(path: '/api/scans/{scan}', tags: ['Professionnel'], summary: 'Afficher scan', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'scan', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function scansShow(): void {}

    #[OA\Delete(path: '/api/scans/{scan}', tags: ['Professionnel'], summary: 'Supprimer scan', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'scan', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'Supprimé')])]
    public function scansDestroy(): void {}
}
