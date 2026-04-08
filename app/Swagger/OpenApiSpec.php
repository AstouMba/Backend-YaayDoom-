<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'YaayDoom API',
    description: 'Documentation OpenAPI alignée sur le backend courant et le contrat frontend UUID-only'
)]
#[OA\Server(url: 'http://127.0.0.1:8000', description: 'Serveur local')]
#[OA\Tag(name: 'Auth')]
#[OA\Tag(name: 'Admin')]
#[OA\Tag(name: 'Maman')]
#[OA\Tag(name: 'Professionnel')]
#[OA\Tag(name: 'Famille')]
#[OA\Tag(name: 'Scan')]
#[OA\SecurityScheme(
    securityScheme: 'Bearer',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT'
)]
class OpenApiSpec
{
    #[OA\Post(
        path: '/api/auth/register',
        tags: ['Auth'],
        summary: 'Inscription utilisateur',
        description: "La maman s'inscrit en une étape avec téléphone, nom complet, date de naissance et mot de passe. Le professionnel fournit aussi ses informations de métier et reste en attente de validation admin.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['fullName', 'phone', 'password', 'password_confirmation', 'role'],
                properties: [
                    new OA\Property(property: 'fullName', type: 'string', example: 'Aminata Diallo'),
                    new OA\Property(property: 'phone', type: 'string', example: '+221771234567'),
                    new OA\Property(property: 'birthDate', type: 'string', format: 'date', nullable: true, example: '1992-03-15'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', nullable: true, example: 'aminata@example.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'password123'),
                    new OA\Property(property: 'password_confirmation', type: 'string', example: 'password123'),
                    new OA\Property(property: 'role', type: 'string', enum: ['maman', 'professionnel'], example: 'maman'),
                    new OA\Property(property: 'specialty', type: 'string', nullable: true, example: 'Gynécologue'),
                    new OA\Property(property: 'matricule', type: 'string', nullable: true, example: 'GYN-2024-001'),
                    new OA\Property(property: 'healthCenter', type: 'string', nullable: true, example: 'Hôpital Principal de Dakar'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Compte créé', content: new OA\JsonContent(example: [
            'success' => true,
            'user' => [
                'id' => 'c1f0f2ce-8a34-4f95-8a3a-4c9d33a0b1d1',
                'nom' => 'Aminata Diallo',
                'telephone' => '+221771234567',
                'date_naissance' => '1992-03-15',
                'role' => 'maman',
                'specialite' => null,
                'statut' => 'actif',
                'is_validated' => true,
            ],
            'token' => 'jwt-token-here',
            'access_token' => 'jwt-token-here',
            'message' => 'Compte créé avec succès.',
        ])),
            new OA\Response(response: 422, description: 'Erreur de validation', content: new OA\JsonContent(example: [
                'message' => 'Le formulaire contient des erreurs',
                'errors' => [
                    'email' => ['Le champ est requis'],
                    'password' => ['Le champ est requis'],
                ],
            ])),
        ]
    )]
    public function authRegister(): void {}

    #[OA\Post(
        path: '/api/auth/login',
        tags: ['Auth'],
        summary: 'Connexion utilisateur',
        description: "La maman se connecte avec son téléphone. Le professionnel et l'admin se connectent avec leur email.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['loginId', 'password'],
                properties: [
                    new OA\Property(property: 'loginId', type: 'string', example: '+221771234567'),
                    new OA\Property(property: 'password', type: 'string', example: 'demo1234'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Connexion réussie', content: new OA\JsonContent(example: [
            'token' => 'jwt-token-here',
            'user' => [
                'id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11',
                'nom' => 'Fatou Diop',
                'telephone' => '+221 77 123 45 67',
                'date_naissance' => '1991-04-10',
                'role' => 'maman',
                'specialite' => null,
                'matricule' => null,
                'centre_de_sante' => null,
                'is_validated' => true,
                'statut' => 'actif',
            ],
        ])),
            new OA\Response(response: 422, description: 'Erreur de validation', content: new OA\JsonContent(example: [
                'message' => 'Le formulaire contient des erreurs',
                'errors' => [
                    'loginId' => ['Le champ est requis'],
                    'password' => ['Le champ est requis'],
                ],
            ])),
            new OA\Response(response: 401, description: 'Identifiants invalides', content: new OA\JsonContent(example: [
                'message' => 'Identifiants invalides',
            ])),
        ]
    )]
    public function authLogin(): void {}

    #[OA\Post(path: '/api/auth/logout', tags: ['Auth'], summary: 'Déconnexion', security: [['Bearer' => []]], responses: [
        new OA\Response(response: 200, description: 'Déconnecté', content: new OA\JsonContent(example: ['success' => true])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Non authentifié'])),
    ])]
    public function authLogout(): void {}

    #[OA\Get(path: '/api/auth/me', tags: ['Auth'], summary: 'Utilisateur connecté', security: [['Bearer' => []]], responses: [
        new OA\Response(response: 200, description: 'Profil', content: new OA\JsonContent(example: [
        'id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11',
        'nom' => 'Fatou Diop',
        'telephone' => '+221 77 123 45 67',
        'date_naissance' => '1991-04-10',
        'role' => 'maman',
        'specialite' => null,
        'matricule' => null,
        'centre_de_sante' => null,
        'is_validated' => true,
        'statut' => 'actif',
    ])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Non authentifié'])),
    ])]
    public function authMe(): void {}

    #[OA\Put(path: '/api/auth/profile', tags: ['Auth'], summary: 'Mettre à jour le profil connecté', security: [['Bearer' => []]], responses: [
        new OA\Response(response: 200, description: 'Profil mis à jour', content: new OA\JsonContent(example: [
        'id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11',
        'nom' => 'Fatou Diop',
        'email' => 'fatou.diop@email.com',
        'telephone' => '+221 77 123 45 67',
        'role' => 'professionnel',
        'specialite' => 'Sage-femme',
        'statut' => 'actif',
        'is_validated' => true,
    ])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 422, description: 'Erreur de validation', content: new OA\JsonContent(example: [
            'message' => 'Le formulaire contient des erreurs.',
            'errors' => ['email' => ['Le champ doit être une adresse email valide']],
        ])),
    ])]
    public function authProfile(): void {}

    #[OA\Patch(path: '/api/auth/password', tags: ['Auth'], summary: 'Changer le mot de passe', security: [['Bearer' => []]], responses: [
        new OA\Response(response: 200, description: 'Mot de passe modifié', content: new OA\JsonContent(example: ['success' => true, 'message' => 'Mot de passe mis à jour avec succès.'])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Non authentifié'])),
        new OA\Response(response: 422, description: 'Erreur de validation', content: new OA\JsonContent(example: [
            'message' => 'Le formulaire contient des erreurs.',
            'errors' => ['newPassword' => ['Le champ est requis.']],
        ])),
    ])]
    public function authPassword(): void {}

    #[OA\Post(path: '/api/auth/professional/documents', tags: ['Auth', 'Professionnel'], summary: 'Uploader les documents de validation du professionnel', security: [['Bearer' => []]], requestBody: new OA\RequestBody(
        required: true,
        content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(
                type: 'object',
                required: ['documents'],
                properties: [
                    new OA\Property(
                        property: 'documents',
                        type: 'array',
                        items: new OA\Items(type: 'string', format: 'binary')
                    ),
                ]
            )
        )
    ), responses: [
        new OA\Response(response: 200, description: 'Documents uploadés', content: new OA\JsonContent(example: [
            'success' => true,
            'message' => 'Documents uploadés avec succès.',
            'user' => [
                'id' => '3a7dbd62-8f9d-4d1e-b7c4-0b2c90a5d111',
                'nom' => 'Dr. Mariama Ba',
                'documentUrl' => '/storage/professionnels/3a7dbd62-8f9d-4d1e-b7c4-0b2c90a5d111/verification/attestation.pdf',
                'documents' => [
                    [
                        'name' => 'attestation.pdf',
                        'url' => '/storage/professionnels/3a7dbd62-8f9d-4d1e-b7c4-0b2c90a5d111/verification/attestation.pdf',
                        'mime' => 'application/pdf',
                    ],
                ],
            ],
        ])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Non authentifié'])),
        new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
        new OA\Response(response: 422, description: 'Erreur de validation', content: new OA\JsonContent(example: [
            'message' => 'Le formulaire contient des erreurs',
            'errors' => [
                'documents' => ['Le champ est requis.'],
            ],
        ])),
    ])]
    public function authProfessionalDocuments(): void {}

    #[OA\Get(path: '/api/admin/users', tags: ['Admin'], summary: 'Lister les utilisateurs', security: [['Bearer' => []]], responses: [
        new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(example: [
            [
                'id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11',
                'nom' => 'Fatou Diop',
                'email' => 'fatou.diop@email.com',
                'telephone' => '+221 77 123 45 67',
                'role' => 'maman',
                'specialite' => null,
                'dateInscription' => '2025-01-15',
                'statut' => 'actif',
            ],
        ])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
    ])]
    public function adminUsers(): void {}

    #[OA\Get(path: '/api/admin/stats', tags: ['Admin'], summary: 'Statistiques admin', security: [['Bearer' => []]], responses: [
        new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(example: [
            'totalMamans' => 156,
            'totalProfessionnels' => 23,
            'grossessesActives' => 89,
            'professionnelsEnAttente' => 8,
            'consultationsTotal' => 342,
            'vaccinationsTotal' => 1087,
            'grossessesParMois' => [12, 19, 15, 22, 18, 25, 20, 28, 24, 31, 22, 18],
            'labelsParMois' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
        ])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
    ])]
    public function adminStats(): void {}

    #[OA\Get(path: '/api/admin/professionnels/pending', tags: ['Admin'], summary: 'Professionnels en attente', security: [['Bearer' => []]], responses: [
        new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(example: [
            [
                'id' => '3a7dbd62-8f9d-4d1e-b7c4-0b2c90a5d111',
                'nom' => 'Dr. Aminata Ba',
                'email' => 'aminata.ba@hopital.sn',
                'telephone' => '+221 77 123 45 67',
                'specialite' => 'Gynécologue',
                'matricule' => 'GYN-2024-001',
                'centreDesante' => 'Hôpital Principal de Dakar',
                'documentUrl' => '/storage/professionnels/3a7dbd62-8f9d-4d1e-b7c4-0b2c90a5d111/verification/attestation.pdf',
                'documents' => [
                    [
                        'name' => 'attestation.pdf',
                        'url' => '/storage/professionnels/3a7dbd62-8f9d-4d1e-b7c4-0b2c90a5d111/verification/attestation.pdf',
                    ],
                ],
                'dateInscription' => '2025-01-15',
            ],
        ])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
    ])]
    public function adminPendingProfessionnels(): void {}

    #[OA\Post(path: '/api/admin/professionnels/{user}/approve', tags: ['Admin'], summary: 'Approuver un professionnel', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'user', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['motif'],
            properties: [
                new OA\Property(property: 'motif', type: 'string', example: 'Documents conformes'),
            ]
        )
    ), responses: [
        new OA\Response(response: 200, description: 'Approuvé', content: new OA\JsonContent(example: [
            'success' => true,
            'message' => 'Professionnel approuvé',
            'motif' => 'Documents conformes',
            'professionnel' => [
                'id' => '3a7dbd62-8f9d-4d1e-b7c4-0b2c90a5d111',
                'nom' => 'Dr. Aminata Ba',
                'decisionStatus' => 'approved',
                'decisionMotif' => 'Documents conformes',
                'decisionDate' => '2026-04-07 10:15:00',
                'decisionBy' => '8e4d7b4d-1f5a-4f7d-9d2b-3dcd8c0d7a20',
            ],
        ])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
        new OA\Response(response: 404, description: 'Non trouvé', content: new OA\JsonContent(example: ['message' => 'Utilisateur introuvable.'])),
        new OA\Response(response: 422, description: 'Erreur de validation', content: new OA\JsonContent(example: [
            'message' => 'Le formulaire contient des erreurs.',
            'errors' => ['motif' => ['Le champ est requis.']],
        ])),
    ])]
    public function adminApproveProfessionnel(): void {}

    #[OA\Post(path: '/api/admin/professionnels/{user}/reject', tags: ['Admin'], summary: 'Rejeter un professionnel', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'user', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['motif'],
            properties: [
                new OA\Property(property: 'motif', type: 'string', example: 'Document incomplet'),
            ]
        )
    ), responses: [
        new OA\Response(response: 200, description: 'Rejeté', content: new OA\JsonContent(example: [
            'success' => true,
            'message' => 'Demande rejetée',
            'motif' => 'Document incomplet',
            'professionnel' => [
                'id' => '3a7dbd62-8f9d-4d1e-b7c4-0b2c90a5d111',
                'nom' => 'Dr. Aminata Ba',
                'decisionStatus' => 'rejected',
                'decisionMotif' => 'Document incomplet',
                'decisionDate' => '2026-04-07 10:15:00',
                'decisionBy' => '8e4d7b4d-1f5a-4f7d-9d2b-3dcd8c0d7a20',
            ],
        ])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
        new OA\Response(response: 404, description: 'Non trouvé', content: new OA\JsonContent(example: ['message' => 'Utilisateur introuvable.'])),
        new OA\Response(response: 422, description: 'Erreur de validation', content: new OA\JsonContent(example: [
            'message' => 'Le formulaire contient des erreurs.',
            'errors' => ['motif' => ['Le champ est requis.']],
        ])),
    ])]
    public function adminRejectProfessionnel(): void {}

    #[OA\Patch(path: '/api/admin/users/{user}/role', tags: ['Admin'], summary: 'Changer le rôle', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'user', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], responses: [
        new OA\Response(response: 200, description: 'Rôle modifié', content: new OA\JsonContent(example: ['success' => true])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
        new OA\Response(response: 404, description: 'Non trouvé', content: new OA\JsonContent(example: ['message' => 'Utilisateur introuvable.'])),
        new OA\Response(response: 422, description: 'Erreur de validation', content: new OA\JsonContent(example: [
            'message' => 'Le formulaire contient des erreurs.',
            'errors' => ['role' => ['Le champ est requis.']],
        ])),
    ])]
    public function adminUpdateUserRole(): void {}

    #[OA\Patch(path: '/api/admin/users/{user}/status', tags: ['Admin'], summary: 'Changer le statut', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'user', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], responses: [
        new OA\Response(response: 200, description: 'Statut modifié', content: new OA\JsonContent(example: ['success' => true])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
        new OA\Response(response: 404, description: 'Non trouvé', content: new OA\JsonContent(example: ['message' => 'Utilisateur introuvable.'])),
        new OA\Response(response: 422, description: 'Erreur de validation', content: new OA\JsonContent(example: [
            'message' => 'Le formulaire contient des erreurs.',
            'errors' => ['status' => ['Le champ est requis.']],
        ])),
    ])]
    public function adminUpdateUserStatus(): void {}

    #[OA\Get(path: '/api/users/{user}', tags: ['Admin'], summary: 'Afficher un utilisateur', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'user', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], responses: [
        new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(example: [
            'id' => '2fcb4f4f-7f18-4a44-9b53-b736f0af6d11',
            'nom' => 'Dr. Aminata Ba',
            'email' => 'aminata.ba@hopital.sn',
            'telephone' => '+221 77 234 56 78',
            'role' => 'professionnel',
            'specialite' => 'Gynécologue',
            'dateInscription' => '2024-08-05',
            'statut' => 'actif',
        ])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
        new OA\Response(response: 404, description: 'Non trouvé', content: new OA\JsonContent(example: ['message' => 'Utilisateur introuvable.'])),
    ])]
    public function usersShow(): void {}

    #[OA\Put(path: '/api/users/{user}', tags: ['Admin'], summary: 'Mettre à jour un utilisateur', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'user', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], responses: [
        new OA\Response(response: 200, description: 'Modifié', content: new OA\JsonContent(example: [
            'id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11',
            'nom' => 'Fatou Diop',
            'email' => 'fatou.diop@email.com',
            'telephone' => '+221 77 123 45 67',
            'role' => 'maman',
            'specialite' => null,
            'dateInscription' => '2024-09-10',
            'statut' => 'actif',
        ])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
        new OA\Response(response: 404, description: 'Non trouvé', content: new OA\JsonContent(example: ['message' => 'Utilisateur introuvable.'])),
        new OA\Response(response: 422, description: 'Erreur de validation', content: new OA\JsonContent(example: [
            'message' => 'Le formulaire contient des erreurs.',
            'errors' => ['email' => ['Le champ doit être une adresse email valide.']],
        ])),
    ])]
    public function usersUpdate(): void {}

    #[OA\Delete(path: '/api/users/{user}', tags: ['Admin'], summary: 'Supprimer un utilisateur', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'user', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], responses: [new OA\Response(response: 200, description: 'Supprimé')])]
    public function usersDestroy(): void {}

    #[OA\Get(path: '/api/grossesses', tags: ['Maman', 'Professionnel'], summary: 'Lister les grossesses', security: [['Bearer' => []]], responses: [
        new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(example: [
            [
                'id' => 'a31d3b9a-5aa5-4e9f-9f66-9f48ff1d7f20',
                'maman_id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11',
                'maman_nom' => 'Fatou Diop',
                'date_debut' => '2025-01-05',
                'date_fin_prevue' => '2025-10-12',
                'semaine_grossesse' => 10,
                'nombre_grossesses_precedentes' => 0,
                'antecedents_medicaux' => '',
                'statut' => 'en_attente',
                'professionnel_validateur' => null,
                'date_validation' => null,
                'trimestre' => 1,
                'notes' => '',
            ],
        ])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
    ])]
    public function grossessesIndex(): void {}

    #[OA\Get(path: '/api/grossesses/{grossesse}', tags: ['Maman', 'Professionnel'], summary: 'Afficher une grossesse', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'grossesse', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], responses: [
        new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(example: [
            'id' => 'a31d3b9a-5aa5-4e9f-9f66-9f48ff1d7f20',
            'maman_id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11',
            'maman_nom' => 'Fatou Diop',
            'date_debut' => '2025-01-05',
            'date_fin_prevue' => '2025-10-12',
            'semaine_grossesse' => 10,
            'nombre_grossesses_precedentes' => 0,
            'antecedents_medicaux' => '',
            'statut' => 'en_attente',
            'professionnel_validateur' => null,
            'date_validation' => null,
            'trimestre' => 1,
            'notes' => '',
        ])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
        new OA\Response(response: 404, description: 'Non trouvé', content: new OA\JsonContent(example: ['message' => 'Grossesse introuvable.'])),
    ])]
    public function grossessesShow(): void {}

    #[OA\Post(
        path: '/api/grossesses',
        tags: ['Maman', 'Professionnel'],
        summary: 'Créer une grossesse',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['date_debut'],
                properties: [
                    new OA\Property(property: 'date_debut', type: 'string', format: 'date', example: '2026-03-28'),
                    new OA\Property(property: 'date_fin_prevue', type: 'string', format: 'date', nullable: true, example: '2026-12-05'),
                    new OA\Property(property: 'nombre_grossesses_precedentes', type: 'integer', nullable: true, example: 0),
                    new OA\Property(property: 'antecedents_medicaux', type: 'string', nullable: true, example: ''),
                    new OA\Property(property: 'notes', type: 'string', nullable: true, example: 'Suivi initial'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Créé', content: new OA\JsonContent(example: [
            'id' => 'a31d3b9a-5aa5-4e9f-9f66-9f48ff1d7f20',
            'maman_id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11',
            'date_debut' => '2025-01-05',
            'date_fin_prevue' => '2025-10-12',
            'statut' => 'en_attente',
            'notes' => 'Suivi initial',
        ])),
            new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
            new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
            new OA\Response(response: 422, description: 'Erreur de validation', content: new OA\JsonContent(example: [
                'message' => 'Le formulaire contient des erreurs.',
                'errors' => ['date_debut' => ['Le champ est requis.']],
            ])),
        ]
    )]
    public function grossessesStore(): void {}

    #[OA\Put(path: '/api/grossesses/{grossesse}', tags: ['Maman', 'Professionnel'], summary: 'Mettre à jour une grossesse', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'grossesse', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], responses: [
        new OA\Response(response: 200, description: 'Modifié', content: new OA\JsonContent(example: ['id' => 'a31d3b9a-5aa5-4e9f-9f66-9f48ff1d7f20', 'maman_id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11', 'statut' => 'validee'])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
        new OA\Response(response: 404, description: 'Non trouvé', content: new OA\JsonContent(example: ['message' => 'Grossesse introuvable.'])),
        new OA\Response(response: 422, description: 'Erreur de validation', content: new OA\JsonContent(example: [
            'message' => 'Le formulaire contient des erreurs.',
            'errors' => ['date_fin_prevue' => ['Le champ doit être une date valide.']],
        ])),
    ])]
    public function grossessesUpdatePut(): void {}

    #[OA\Patch(path: '/api/grossesses/{grossesse}', tags: ['Maman', 'Professionnel'], summary: 'Mettre à jour partiellement une grossesse', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'grossesse', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], responses: [
        new OA\Response(response: 200, description: 'Modifié', content: new OA\JsonContent(example: ['id' => 'a31d3b9a-5aa5-4e9f-9f66-9f48ff1d7f20', 'maman_id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11', 'statut' => 'validee'])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
        new OA\Response(response: 404, description: 'Non trouvé', content: new OA\JsonContent(example: ['message' => 'Grossesse introuvable.'])),
    ])]
    public function grossessesUpdatePatch(): void {}

    #[OA\Get(path: '/api/bebes', tags: ['Maman', 'Professionnel'], summary: 'Lister les bébés', security: [['Bearer' => []]], responses: [
        new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(example: [
            [
                'id' => 'b5ad1fbe-2c58-4c2b-9a5d-9f6394f5db01',
                'grossesse_id' => 'a31d3b9a-5aa5-4e9f-9f66-9f48ff1d7f20',
                'maman_id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11',
                'nom' => 'Moussa Diallo',
                'date_naissance' => '2025-10-12',
                'sexe' => 'M',
                'poids' => 3.1,
                'taille' => 49,
                'groupe_sanguin' => 'O+',
            ],
        ])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
    ])]
    public function bebesIndex(): void {}

    #[OA\Get(path: '/api/bebes/{bebe}', tags: ['Maman', 'Professionnel'], summary: 'Afficher un bébé', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'bebe', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], responses: [
        new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(example: [
            'id' => 'b5ad1fbe-2c58-4c2b-9a5d-9f6394f5db01',
            'grossesse_id' => 'a31d3b9a-5aa5-4e9f-9f66-9f48ff1d7f20',
            'maman_id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11',
            'nom' => 'Moussa Diallo',
            'date_naissance' => '2025-10-12',
            'sexe' => 'M',
            'poids' => 3.1,
            'taille' => 49,
            'groupe_sanguin' => 'O+',
        ])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
        new OA\Response(response: 404, description: 'Non trouvé', content: new OA\JsonContent(example: ['message' => 'Bébé introuvable.'])),
    ])]
    public function bebesShow(): void {}

    #[OA\Post(
        path: '/api/bebes',
        tags: ['Maman', 'Professionnel'],
        summary: 'Créer un bébé',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['maman_id', 'grossesse_id', 'nom', 'date_naissance', 'sexe'],
                properties: [
                    new OA\Property(property: 'maman_id', type: 'string', format: 'uuid', example: '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11'),
                    new OA\Property(property: 'grossesse_id', type: 'string', format: 'uuid', example: 'a31d3b9a-5aa5-4e9f-9f66-9f48ff1d7f20'),
                    new OA\Property(property: 'nom', type: 'string', example: 'Moussa Diallo'),
                    new OA\Property(property: 'date_naissance', type: 'string', format: 'date', example: '2026-03-28'),
                    new OA\Property(property: 'sexe', type: 'string', example: 'M'),
                    new OA\Property(property: 'poids', type: 'number', format: 'float', nullable: true),
                    new OA\Property(property: 'taille', type: 'number', format: 'float', nullable: true),
                    new OA\Property(property: 'groupe_sanguin', type: 'string', nullable: true, example: 'O+'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Créé', content: new OA\JsonContent(example: [
                'id' => 'b5ad1fbe-2c58-4c2b-9a5d-9f6394f5db01',
                'maman_id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11',
                'grossesse_id' => 'a31d3b9a-5aa5-4e9f-9f66-9f48ff1d7f20',
                'nom' => 'Moussa Diallo',
                'date_naissance' => '2025-10-12',
                'sexe' => 'M',
                'poids' => 3.1,
                'taille' => 49,
                'groupe_sanguin' => 'O+',
            ])),
            new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
            new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
            new OA\Response(response: 422, description: 'Erreur de validation', content: new OA\JsonContent(example: [
                'message' => 'Le formulaire contient des erreurs.',
                'errors' => ['grossesse_id' => ['Le champ est requis.']],
            ])),
        ]
    )]
    public function bebesStore(): void {}

    #[OA\Get(path: '/api/consultations', tags: ['Professionnel'], summary: 'Lister les consultations', security: [['Bearer' => []]], responses: [
        new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(example: [
            [
                'id' => 'd1f0d0f1-4d0d-4a8d-b1f7-9d66e40e9c44',
                'maman_id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11',
                'professionnel_id' => '2fcb4f4f-7f18-4a44-9b53-b736f0af6d11',
                'type' => 'Consultation prénatale',
                'date' => '2025-04-05',
                'heure' => '09:30:00',
                'tension_arterielle' => '12/8',
                'poids' => '68',
                'hauteur_uterine' => '28',
                'bcf' => '145',
                'notes' => 'Contrôle routine',
                'semaine_grossesse' => 24,
            ],
        ])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
    ])]
    public function consultationsIndex(): void {}

    #[OA\Get(path: '/api/consultations/{consultation}', tags: ['Professionnel'], summary: 'Afficher une consultation', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'consultation', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], responses: [
        new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(example: [
            [
                'id' => 'd1f0d0f1-4d0d-4a8d-b1f7-9d66e40e9c44',
                'maman_id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11',
                'professionnel_id' => '2fcb4f4f-7f18-4a44-9b53-b736f0af6d11',
                'type' => 'Consultation prénatale',
                'date' => '2025-04-05',
                'heure' => '09:30:00',
                'tension_arterielle' => '12/8',
                'poids' => '68',
                'hauteur_uterine' => '28',
                'bcf' => '145',
                'notes' => 'Contrôle routine',
                'semaine_grossesse' => 24,
            ],
        ])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
        new OA\Response(response: 404, description: 'Non trouvé', content: new OA\JsonContent(example: ['message' => 'Consultation introuvable.'])),
    ])]
    public function consultationsShow(): void {}

    #[OA\Post(
        path: '/api/consultations',
        tags: ['Professionnel'],
        summary: 'Créer une consultation',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['maman_id', 'professionnel_id', 'date', 'heure', 'type'],
                properties: [
                    new OA\Property(property: 'maman_id', type: 'string', format: 'uuid', example: '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11'),
                    new OA\Property(property: 'professionnel_id', type: 'string', format: 'uuid', example: '2fcb4f4f-7f18-4a44-9b53-b736f0af6d11'),
                    new OA\Property(property: 'date', type: 'string', format: 'date', example: '2026-04-05'),
                    new OA\Property(property: 'heure', type: 'string', example: '09:30:00'),
                    new OA\Property(property: 'type', type: 'string', example: 'Consultation prénatale'),
                    new OA\Property(property: 'tension_arterielle', type: 'string', nullable: true, example: '12/8'),
                    new OA\Property(property: 'poids', type: 'number', format: 'float', nullable: true, example: 68),
                    new OA\Property(property: 'hauteur_uterine', type: 'number', format: 'float', nullable: true, example: 28),
                    new OA\Property(property: 'bcf', type: 'string', nullable: true, example: '145'),
                    new OA\Property(property: 'semaine_grossesse', type: 'integer', nullable: true, example: 24),
                    new OA\Property(property: 'notes', type: 'string', nullable: true, example: 'Contrôle routine'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Créé', content: new OA\JsonContent(example: [
            'id' => 'e9a5b0a8-4b2a-4f6e-a9fa-0fae5e92d110',
            'maman_id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11',
            'professionnel_id' => '2fcb4f4f-7f18-4a44-9b53-b736f0af6d11',
            'date' => '2025-04-05',
            'heure' => '09:30:00',
            'type' => 'Consultation de suivi',
            'notes' => 'Tension stable',
            'tension_arterielle' => '12/8',
            'poids' => '68',
            'hauteur_uterine' => '28',
            'bcf' => '145',
            'semaine_grossesse' => 24,
        ])),
            new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
            new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
            new OA\Response(response: 422, description: 'Erreur de validation', content: new OA\JsonContent(example: [
                'message' => 'Le formulaire contient des erreurs.',
                'errors' => ['maman_id' => ['Le champ est requis.']],
            ])),
        ]
    )]
    public function consultationsStore(): void {}

    #[OA\Patch(path: '/api/consultations/{consultation}', tags: ['Professionnel'], summary: 'Mettre à jour une consultation', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'consultation', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], responses: [
        new OA\Response(response: 200, description: 'Modifié', content: new OA\JsonContent(example: ['id' => 'e9a5b0a8-4b2a-4f6e-a9fa-0fae5e92d110', 'notes' => 'Évolution favorable', 'poids' => '68.5'])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
        new OA\Response(response: 404, description: 'Non trouvé', content: new OA\JsonContent(example: ['message' => 'Consultation introuvable.'])),
    ])]
    public function consultationsUpdate(): void {}

    #[OA\Get(path: '/api/vaccinations', tags: ['Maman', 'Professionnel'], summary: 'Lister les vaccinations', security: [['Bearer' => []]], responses: [
        new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(example: [
            [
                'id' => '6e1b4b57-8a12-45ce-a5e1-6de8e9a21c11',
                'bebe_id' => 'b5ad1fbe-2c58-4c2b-9a5d-9f6394f5db01',
                'nom_vaccin' => 'BCG',
                'age' => 'À la naissance',
                'date_vaccination' => '2025-03-15',
                'prochaine_dose' => null,
                'notes' => '',
                'professionnel_id' => '2fcb4f4f-7f18-4a44-9b53-b736f0af6d11',
            ],
        ])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
    ])]
    public function vaccinationsIndex(): void {}

    #[OA\Get(path: '/api/vaccinations/{vaccination}', tags: ['Maman', 'Professionnel'], summary: 'Afficher une vaccination', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'vaccination', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], responses: [
        new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(example: [
            'id' => '6e1b4b57-8a12-45ce-a5e1-6de8e9a21c11',
            'bebe_id' => 'b5ad1fbe-2c58-4c2b-9a5d-9f6394f5db01',
            'nom_vaccin' => 'BCG',
            'age' => 'À la naissance',
            'date_vaccination' => '2025-03-15',
            'prochaine_dose' => null,
            'notes' => '',
            'professionnel_id' => '2fcb4f4f-7f18-4a44-9b53-b736f0af6d11',
        ])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
        new OA\Response(response: 404, description: 'Non trouvé', content: new OA\JsonContent(example: ['message' => 'Vaccination introuvable.'])),
    ])]
    public function vaccinationsShow(): void {}

    #[OA\Post(
        path: '/api/vaccinations',
        tags: ['Maman', 'Professionnel'],
        summary: 'Créer une vaccination',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['bebe_id', 'nom_vaccin', 'date_vaccination'],
                properties: [
                    new OA\Property(property: 'bebe_id', type: 'string', format: 'uuid', example: 'b5ad1fbe-2c58-4c2b-9a5d-9f6394f5db01'),
                    new OA\Property(property: 'nom_vaccin', type: 'string', example: 'BCG'),
                    new OA\Property(property: 'age', type: 'string', nullable: true, example: 'À la naissance'),
                    new OA\Property(property: 'date_vaccination', type: 'string', format: 'date', example: '2026-03-28'),
                    new OA\Property(property: 'prochaine_dose', type: 'string', format: 'date', nullable: true),
                    new OA\Property(property: 'notes', type: 'string', nullable: true, example: 'Administré à la maternité'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Créé', content: new OA\JsonContent(example: [
            'id' => '2c9c2bb4-41c7-4a70-b8c9-41f92f6e33d1',
            'bebe_id' => 'b5ad1fbe-2c58-4c2b-9a5d-9f6394f5db01',
            'nom_vaccin' => 'BCG',
            'date_vaccination' => '2025-03-15',
            'prochaine_dose' => null,
            'notes' => 'Administré à la maternité',
            'professionnel_id' => '2fcb4f4f-7f18-4a44-9b53-b736f0af6d11',
        ])),
            new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
            new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
            new OA\Response(response: 422, description: 'Erreur de validation', content: new OA\JsonContent(example: [
                'message' => 'Le formulaire contient des erreurs.',
                'errors' => ['bebe_id' => ['Le champ est requis.']],
            ])),
        ]
    )]
    public function vaccinationsStore(): void {}

    #[OA\Patch(path: '/api/vaccinations/{vaccination}', tags: ['Maman', 'Professionnel'], summary: 'Mettre à jour une vaccination', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'vaccination', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], responses: [
        new OA\Response(response: 200, description: 'Modifié', content: new OA\JsonContent(example: ['id' => '2c9c2bb4-41c7-4a70-b8c9-41f92f6e33d1', 'bebe_id' => 'b5ad1fbe-2c58-4c2b-9a5d-9f6394f5db01', 'nom_vaccin' => 'BCG', 'date_vaccination' => '2025-03-15'])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
        new OA\Response(response: 404, description: 'Non trouvé', content: new OA\JsonContent(example: ['message' => 'Vaccination introuvable.'])),
    ])]
    public function vaccinationsUpdate(): void {}

    #[OA\Get(path: '/api/rendez-vous', tags: ['Maman'], summary: 'Lister les rendez-vous', security: [['Bearer' => []]], responses: [
        new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(example: [
            [
                'id' => '5d3a8d33-0b6b-4f5f-9f85-2a2a8d4c7c11',
                'maman_id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11',
                'grossesse_id' => 'a31d3b9a-5aa5-4e9f-9f66-9f48ff1d7f20',
                'type' => 'Consultation prénatale',
                'motif' => 'Suivi mensuel',
                'date' => '2025-04-20',
                'heure' => '14:00',
                'professionnel_id' => '2fcb4f4f-7f18-4a44-9b53-b736f0af6d11',
                'professionnel' => 'Dr. Fatou Sow',
                'lieu' => 'Hôpital Principal de Dakar',
                'statut' => 'prévu',
                'notes' => '',
            ],
        ])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
    ])]
    public function rendezVousIndex(): void {}

    #[OA\Post(
        path: '/api/rendez-vous',
        tags: ['Maman'],
        summary: 'Créer un rendez-vous',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['grossesse_id', 'type', 'motif', 'date', 'heure', 'professionnel_id', 'lieu'],
                properties: [
                    new OA\Property(property: 'grossesse_id', type: 'string', format: 'uuid', example: 'a31d3b9a-5aa5-4e9f-9f66-9f48ff1d7f20'),
                    new OA\Property(property: 'type', type: 'string', example: 'Consultation prénatale'),
                    new OA\Property(property: 'motif', type: 'string', example: 'Suivi mensuel'),
                    new OA\Property(property: 'date', type: 'string', format: 'date', example: '2026-04-20'),
                    new OA\Property(property: 'heure', type: 'string', example: '14:00'),
                    new OA\Property(property: 'professionnel_id', type: 'string', format: 'uuid', example: '2fcb4f4f-7f18-4a44-9b53-b736f0af6d11'),
                    new OA\Property(property: 'lieu', type: 'string', example: 'Hôpital Principal de Dakar'),
                    new OA\Property(property: 'notes', type: 'string', nullable: true, example: ''),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Créé', content: new OA\JsonContent(example: [
            'id' => '5d3a8d33-0b6b-4f5f-9f85-2a2a8d4c7c11',
            'maman_id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11',
            'grossesse_id' => 'a31d3b9a-5aa5-4e9f-9f66-9f48ff1d7f20',
            'type' => 'Consultation prénatale',
            'motif' => 'Suivi mensuel',
            'date' => '2025-04-20',
            'heure' => '14:00',
            'professionnel_id' => '2fcb4f4f-7f18-4a44-9b53-b736f0af6d11',
            'lieu' => 'Hôpital Principal de Dakar',
            'statut' => 'prévu',
            'notes' => '',
        ])),
            new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
            new OA\Response(response: 403, description: 'Accès interdit', content: new OA\JsonContent(example: ['message' => 'Forbidden.'])),
            new OA\Response(response: 422, description: 'Erreur de validation', content: new OA\JsonContent(example: [
                'message' => 'Le formulaire contient des erreurs.',
                'errors' => ['grossesse_id' => ['Le champ est requis.']],
            ])),
        ]
    )]
    public function rendezVousStore(): void {}

    #[OA\Get(path: '/api/familles/{uuid}', tags: ['Famille'], summary: 'Afficher le dossier familial', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'uuid', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], responses: [
        new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(example: [
            'id' => 'FAM-001',
            'maman_id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11',
            'est_gemellaire' => false,
            'membres' => [
                ['id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11', 'type' => 'maman', 'lien' => 'mere', 'nom' => 'Fatou Diop', 'est_actif' => true],
                ['id' => 'b5ad1fbe-2c58-4c2b-9a5d-9f6394f5db01', 'type' => 'bebe', 'lien' => 'fille', 'nom' => 'Aminata Jr Diallo', 'est_actif' => true],
            ],
            'grossesses' => [
                ['id' => 'a31d3b9a-5aa5-4e9f-9f66-9f48ff1d7f20', 'maman_id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11', 'statut' => 'validee'],
            ],
            'bebes' => [
                ['id' => 'b5ad1fbe-2c58-4c2b-9a5d-9f6394f5db01', 'maman_id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11', 'nom' => 'Aminata Jr Diallo'],
            ],
            'consultations' => [],
            'vaccinations' => [],
            'rendez_vous' => [],
        ])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 404, description: 'Non trouvé', content: new OA\JsonContent(example: ['message' => 'Famille introuvable.'])),
    ])]
    public function familleShow(): void {}

    #[OA\Get(path: '/api/familles/{uuid}/maman', tags: ['Famille'], summary: 'Bloc maman du dossier', security: [['Bearer' => []]], parameters: [new OA\Parameter(name: 'uuid', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))], responses: [
        new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(example: [
            'maman' => [
                'id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11',
                'nom' => 'Fatou Diop',
                'email' => 'fatou.diop@email.com',
                'telephone' => '+221 77 123 45 67',
            ],
            'grossesse' => [
                'id' => 'a31d3b9a-5aa5-4e9f-9f66-9f48ff1d7f20',
                'statut' => 'validee',
            ],
            'consultations' => [],
            'rendez_vous' => [],
        ])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 404, description: 'Non trouvé', content: new OA\JsonContent(example: ['message' => 'Famille introuvable.'])),
    ])]
    public function familleMaman(): void {}

    #[OA\Get(path: '/api/familles/{uuid}/bebes/{bebeUuid}', tags: ['Famille'], summary: 'Bloc bébé du dossier', security: [['Bearer' => []]], parameters: [
        new OA\Parameter(name: 'uuid', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
        new OA\Parameter(name: 'bebeUuid', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid')),
    ], responses: [
        new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(example: [
            'bebe' => [
                'id' => 'b5ad1fbe-2c58-4c2b-9a5d-9f6394f5db01',
                'nom' => 'Aminata Jr Diallo',
                'date_naissance' => '2025-03-15',
                'sexe' => 'F',
            ],
            'vaccinations' => [],
            'croissance' => [],
        ])),
        new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
        new OA\Response(response: 404, description: 'Non trouvé', content: new OA\JsonContent(example: ['message' => 'Bébé introuvable.'])),
    ])]
    public function familleBebe(): void {}

    #[OA\Post(
        path: '/api/scans/resolve',
        tags: ['Scan'],
        summary: 'Résoudre un QR code vers un patient',
        security: [['Bearer' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['qr_code'],
                properties: [new OA\Property(property: 'qr_code', type: 'string', example: '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11')]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Patient trouvé', content: new OA\JsonContent(example: [
                'type' => 'grossesse',
                'patient' => [
                    'id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11',
                    'nom' => 'Fatou Diop',
                    'telephone' => '+221 77 123 45 67',
                ],
                'grossesse' => [
                    'id' => 'a31d3b9a-5aa5-4e9f-9f66-9f48ff1d7f20',
                    'maman_id' => '9c0a3d40-6f1b-4c2b-9d15-8e0b9d8a7f11',
                    'statut' => 'validee',
                ],
            ])),
            new OA\Response(response: 401, description: 'Non authentifié', content: new OA\JsonContent(example: ['message' => 'Unauthenticated.'])),
            new OA\Response(response: 404, description: 'Patient non trouvé', content: new OA\JsonContent(example: ['message' => 'Patient introuvable'])),
            new OA\Response(response: 422, description: 'Erreur de validation', content: new OA\JsonContent(example: [
                'message' => 'Le champ qr_code est obligatoire.',
                'errors' => ['qr_code' => ['Le champ est requis']],
            ])),
        ]
    )]
    public function scansResolve(): void {}
}
