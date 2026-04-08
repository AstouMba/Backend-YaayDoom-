<?php

namespace Tests\Feature;

use App\Models\Bebe;
use App\Models\Consultation;
use App\Models\Grossesse;
use App\Models\RendezVous;
use App\Models\User;
use App\Models\Vaccination;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ApiContractSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_register_returns_contract_payload(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'fullName' => 'Aminata Diallo',
            'phone' => '+221771234567',
            'birthDate' => '1992-03-15',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'maman',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'token',
                'access_token',
                'user' => [
                    'id',
                    'nom',
                    'email',
                    'telephone',
                    'date_naissance',
                    'role',
                    'statut',
                ],
            ]);
    }

    public function test_auth_login_works_with_login_id(): void
    {
        $user = User::create([
            'name' => 'Fatou Diop',
            'email' => null,
            'phone' => '+221771234568',
            'birth_date' => '1991-04-10',
            'password' => 'demo1234',
            'role' => 'maman',
            'status' => 'actif',
            'is_validated' => true,
        ]);

        $response = $this->postJson('/api/auth/login', [
            'loginId' => $user->phone,
            'password' => 'demo1234',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'token',
                'user' => [
                    'id',
                    'nom',
                    'email',
                    'telephone',
                    'date_naissance',
                    'role',
                    'statut',
                ],
            ]);
    }

    public function test_auth_login_respects_role_based_identifier(): void
    {
        $professionnel = User::create([
            'name' => 'Dr. Fatou Sow',
            'email' => 'dr.fatou.' . uniqid() . '@example.com',
            'phone' => '+221771234580',
            'password' => 'demo1234',
            'role' => 'professionnel',
            'status' => 'actif',
            'is_validated' => true,
            'specialite' => 'Gynécologue',
            'matricule' => 'GYN-' . uniqid(),
            'centre_de_sante' => 'Hôpital Principal de Dakar',
        ]);

        $byPhone = $this->postJson('/api/auth/login', [
            'loginId' => $professionnel->phone,
            'password' => 'demo1234',
        ]);

        $byPhone->assertStatus(401);

        $byEmail = $this->postJson('/api/auth/login', [
            'loginId' => $professionnel->email,
            'password' => 'demo1234',
        ]);

        $byEmail->assertOk()
            ->assertJsonStructure([
                'token',
                'user' => [
                    'id',
                    'nom',
                    'email',
                    'telephone',
                    'role',
                    'statut',
                ],
            ]);
    }

    public function test_auth_me_returns_profile_for_authenticated_user(): void
    {
        $user = User::create([
            'name' => 'Seynabou Ndiaye',
            'email' => null,
            'phone' => '+221771234569',
            'birth_date' => '1990-02-21',
            'password' => 'demo1234',
            'role' => 'maman',
            'status' => 'actif',
            'is_validated' => true,
        ]);

        Passport::actingAs($user);

        $response = $this->getJson('/api/auth/me');

        $response->assertOk()
            ->assertJsonStructure([
                'id',
                'nom',
                'email',
                'telephone',
                'date_naissance',
                'role',
                'statut',
            ]);
    }

    public function test_admin_stats_and_users_are_available_for_admin(): void
    {
        $admin = User::create([
            'name' => 'Admin Demo',
            'email' => 'admin.' . uniqid() . '@example.com',
            'phone' => '+221771234570',
            'password' => 'demo1234',
            'role' => 'admin',
            'status' => 'actif',
            'is_validated' => true,
        ]);

        Passport::actingAs($admin);

        $stats = $this->getJson('/api/admin/stats');
        $stats->assertOk()
            ->assertJsonStructure([
                'totalMamans',
                'totalProfessionnels',
                'grossessesActives',
                'professionnelsEnAttente',
                'consultationsTotal',
                'vaccinationsTotal',
                'grossessesParMois',
                'labelsParMois',
            ]);

        $users = $this->getJson('/api/admin/users');
        $users->assertOk();
    }

    public function test_professional_can_upload_verification_documents(): void
    {
        Storage::fake('public');

        $professionnel = User::create([
            'name' => 'Dr. Mariama Ba',
            'email' => 'pro.upload.' . uniqid() . '@example.com',
            'phone' => '+221771234573',
            'password' => 'demo1234',
            'role' => 'professionnel',
            'status' => 'actif',
            'is_validated' => false,
            'specialite' => 'Sage-femme',
            'matricule' => 'SF-' . uniqid(),
            'centre_de_sante' => 'Clinique Mere-Enfant',
        ]);

        Passport::actingAs($professionnel);

        $response = $this->post('/api/auth/professional/documents', [
            'documents' => [
                UploadedFile::fake()->create('attestation.pdf', 120, 'application/pdf'),
            ],
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'user' => [
                    'id',
                    'nom',
                    'documentUrl',
                    'documents',
                ],
            ]);

        $storedFiles = Storage::disk('public')->allFiles('professionnels/' . $professionnel->id . '/verification');

        $this->assertNotEmpty($storedFiles);
    }

    public function test_admin_cannot_approve_professional_without_documents(): void
    {
        $admin = User::create([
            'name' => 'Admin Demo 2',
            'email' => 'admin.block.' . uniqid() . '@example.com',
            'phone' => '+221771234574',
            'password' => 'demo1234',
            'role' => 'admin',
            'status' => 'actif',
            'is_validated' => true,
        ]);

        $professionnel = User::create([
            'name' => 'Dr. Fatou Sow',
            'email' => 'pending.no.docs.' . uniqid() . '@example.com',
            'phone' => '+221771234575',
            'password' => 'demo1234',
            'role' => 'professionnel',
            'status' => 'actif',
            'is_validated' => false,
            'specialite' => 'Gynécologue',
            'matricule' => 'GYN-' . uniqid(),
            'centre_de_sante' => 'Hôpital Principal de Dakar',
        ]);

        Passport::actingAs($admin);

        $response = $this->postJson('/api/admin/professionnels/' . $professionnel->id . '/approve', [
            'motif' => 'Documents conformes',
        ]);

        $response->assertStatus(422);
    }

    public function test_admin_can_approve_and_reject_professionals_with_decision_persistence(): void
    {
        $admin = User::create([
            'name' => 'Admin Decision',
            'email' => 'admin.decision.' . uniqid() . '@example.com',
            'phone' => '+221771234576',
            'password' => 'demo1234',
            'role' => 'admin',
            'status' => 'actif',
            'is_validated' => true,
        ]);

        $professionnel = User::create([
            'name' => 'Dr. Fatou Sow',
            'email' => 'pending.docs.' . uniqid() . '@example.com',
            'phone' => '+221771234577',
            'password' => 'demo1234',
            'role' => 'professionnel',
            'status' => 'actif',
            'is_validated' => false,
            'specialite' => 'Gynécologue',
            'matricule' => 'GYN-' . uniqid(),
            'centre_de_sante' => 'Hôpital Principal de Dakar',
            'verification_documents' => [
                [
                    'name' => 'attestation.pdf',
                    'path' => 'professionnels/' . uniqid() . '/verification/attestation.pdf',
                    'url' => '/storage/professionnels/attestation.pdf',
                    'mime' => 'application/pdf',
                    'size' => 120,
                    'uploaded_at' => now()->toISOString(),
                ],
            ],
        ]);

        Passport::actingAs($admin);

        $approve = $this->postJson('/api/admin/professionnels/' . $professionnel->id . '/approve', [
            'motif' => 'Documents conformes',
        ]);

        $approve->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('motif', 'Documents conformes')
            ->assertJsonPath('professionnel.decisionStatus', 'approved')
            ->assertJsonPath('professionnel.decisionMotif', 'Documents conformes')
            ->assertJsonPath('professionnel.decisionBy', $admin->id);

        $professionnel->refresh();
        $this->assertSame('approved', $professionnel->decision_status);
        $this->assertSame('Documents conformes', $professionnel->decision_motif);
        $this->assertNotNull($professionnel->decision_date);
        $this->assertSame($admin->id, $professionnel->decision_by);

        $rejected = User::create([
            'name' => 'Dr. Mariama Ba',
            'email' => 'pending.reject.' . uniqid() . '@example.com',
            'phone' => '+221771234578',
            'password' => 'demo1234',
            'role' => 'professionnel',
            'status' => 'actif',
            'is_validated' => false,
            'specialite' => 'Sage-femme',
            'matricule' => 'SF-' . uniqid(),
            'centre_de_sante' => 'Clinique Mere-Enfant',
            'verification_documents' => [
                [
                    'name' => 'carte_identite.pdf',
                    'path' => 'professionnels/' . uniqid() . '/verification/carte_identite.pdf',
                    'url' => '/storage/professionnels/carte_identite.pdf',
                    'mime' => 'application/pdf',
                    'size' => 90,
                    'uploaded_at' => now()->toISOString(),
                ],
            ],
        ]);

        $reject = $this->postJson('/api/admin/professionnels/' . $rejected->id . '/reject', [
            'motif' => 'Document incomplet',
        ]);

        $reject->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('motif', 'Document incomplet')
            ->assertJsonPath('professionnel.decisionStatus', 'rejected')
            ->assertJsonPath('professionnel.decisionMotif', 'Document incomplet')
            ->assertJsonPath('professionnel.decisionBy', $admin->id);

        $rejected->refresh();
        $this->assertSame('rejected', $rejected->decision_status);
        $this->assertSame('Document incomplet', $rejected->decision_motif);
        $this->assertNotNull($rejected->decision_date);
        $this->assertSame($admin->id, $rejected->decision_by);
        $this->assertSame('Document incomplet', $rejected->rejection_reason);
    }

    public function test_family_endpoints_return_expected_structure(): void
    {
        $maman = User::create([
            'name' => 'Fatou Diop',
            'email' => null,
            'phone' => '+221771234571',
            'birth_date' => '1992-03-15',
            'password' => 'demo1234',
            'role' => 'maman',
            'status' => 'actif',
            'is_validated' => true,
        ]);

        $professionnel = User::create([
            'name' => 'Dr. Fatou Sow',
            'email' => 'pro.' . uniqid() . '@example.com',
            'phone' => '+221771234572',
            'password' => 'demo1234',
            'role' => 'professionnel',
            'status' => 'actif',
            'is_validated' => true,
            'specialite' => 'Gynécologue',
            'matricule' => 'GYN-' . uniqid(),
            'centre_de_sante' => 'Hôpital Principal de Dakar',
        ]);

        $grossesse = Grossesse::create([
            'maman_id' => $maman->id,
            'date_debut' => '2025-01-05',
            'date_fin_prevue' => '2025-10-12',
            'nombre_grossesses_precedentes' => 0,
            'antecedents_medicaux' => '',
            'professionnel_validateur' => null,
            'date_validation' => null,
            'trimestre' => 1,
            'statut' => 'validee',
            'notes' => '',
        ]);

        $bebe = Bebe::create([
            'maman_id' => $maman->id,
            'grossesse_id' => $grossesse->id,
            'nom' => 'Moussa Diallo',
            'date_naissance' => '2025-10-12',
            'sexe' => 'M',
            'poids' => 3.10,
            'poids_actuel' => 3.10,
            'taille' => 49,
            'taille_actuelle' => 49,
            'groupe_sanguin' => 'O+',
            'notes' => '',
        ]);

        Consultation::create([
            'maman_id' => $maman->id,
            'professionnel_id' => $professionnel->id,
            'date' => '2025-04-05',
            'heure' => '09:30:00',
            'type' => 'Consultation prénatale',
            'tension_arterielle' => '12/8',
            'poids' => 68,
            'hauteur_uterine' => 28,
            'bcf' => '145',
            'semaine_grossesse' => 24,
            'notes' => 'Contrôle routine',
        ]);

        Vaccination::create([
            'bebe_id' => $bebe->id,
            'professionnel_id' => $professionnel->id,
            'nom_vaccin' => 'BCG',
            'age' => 'À la naissance',
            'date_vaccination' => '2025-10-12',
            'prochaine_dose' => null,
            'notes' => 'Administré à la maternité',
        ]);

        RendezVous::create([
            'maman_id' => $maman->id,
            'grossesse_id' => $grossesse->id,
            'type' => 'Consultation prénatale',
            'motif' => 'Suivi mensuel',
            'date' => '2025-04-20',
            'heure' => '14:00',
            'professionnel_id' => $professionnel->id,
            'lieu' => 'Hôpital Principal de Dakar',
            'statut' => 'prévu',
            'notes' => '',
        ]);

        Passport::actingAs($maman);

        $family = $this->getJson('/api/familles/' . $maman->id);
        $family->assertOk()
            ->assertJsonStructure([
                'id',
                'maman_id',
                'est_gemellaire',
                'membres',
                'grossesses',
                'bebes',
                'consultations',
                'vaccinations',
                'rendez_vous',
            ]);

        $mamanBloc = $this->getJson('/api/familles/' . $maman->id . '/maman');
        $mamanBloc->assertOk()
            ->assertJsonStructure([
                    'maman' => [
                        'id',
                        'nom',
                        'email',
                        'telephone',
                    ],
                'grossesse' => [
                    'id',
                    'statut',
                ],
                'consultations',
                'rendez_vous',
            ]);

        $bebeBloc = $this->getJson('/api/familles/' . $maman->id . '/bebes/' . $bebe->id);
        $bebeBloc->assertOk()
            ->assertJsonStructure([
                'bebe' => [
                    'id',
                    'nom',
                    'date_naissance',
                    'sexe',
                ],
                'vaccinations',
                'croissance',
            ]);
    }
}
