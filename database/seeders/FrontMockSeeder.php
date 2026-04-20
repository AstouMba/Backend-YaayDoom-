<?php

namespace Database\Seeders;

use App\Models\Bebe;
use App\Models\Carte;
use App\Models\Consultation;
use App\Models\Grossesse;
use App\Models\RendezVous;
use App\Models\Scan;
use App\Models\User;
use App\Models\Vaccination;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class FrontMockSeeder extends Seeder
{
    public function run(): void
    {
        $users = $this->seedUsers();
        $grossesses = $this->seedGrossesses($users);
        $bebes = $this->seedBebes($users, $grossesses);

        $this->seedCartes($users);
        $this->seedConsultations($users);
        $this->seedRendezVous($users, $grossesses);
        $this->seedVaccinations($users, $bebes);
        $this->seedScans($bebes);
    }

    /**
     * @return array<string, User>
     */
    private function seedUsers(): array
    {
        $users = [];

        $users['professionnel_valide'] = User::where('email', 'pro@demo.com')
            ->firstOrFail();

        $users['maman_fatou'] = $this->seedUser([
            'name' => 'Fatou Sall',
            'phone' => '+221 76 234 56 78',
            'role' => 'maman',
            'status' => 'actif',
            'is_validated' => true,
            'dateInscription' => '2024-10-15',
        ]);

        $users['admin'] = $this->seedUser([
            'email' => 'admin@demo.com',
            'name' => 'Administrateur',
            'phone' => null,
            'role' => 'admin',
            'status' => 'actif',
            'is_validated' => true,
            'dateInscription' => '2024-01-01',
        ]);

        $users['professionnel_attente_1'] = $this->seedUser([
            'email' => 'aminata.ba@hopital.sn',
            'name' => 'Dr. Aminata Ba',
            'phone' => '+221 77 123 45 68',
            'role' => 'professionnel',
            'status' => 'actif',
            'is_validated' => false,
            'specialite' => 'Gynécologue',
            'matricule' => 'GYN-2024-002',
            'centre_de_sante' => 'Hôpital Principal de Dakar',
            'dateInscription' => '2025-01-15',
            'rejection_reason' => null,
        ]);

        $users['professionnel_attente_2'] = $this->seedUser([
            'email' => 'fatou.sall@clinique.sn',
            'name' => 'Fatou Sall',
            'phone' => '+221 76 234 56 80',
            'role' => 'professionnel',
            'status' => 'actif',
            'is_validated' => false,
            'specialite' => 'Sage-femme',
            'matricule' => 'SF-2024-012',
            'centre_de_sante' => 'Clinique de la Mère et de l\'Enfant',
            'dateInscription' => '2025-01-16',
            'rejection_reason' => null,
        ]);

        $users['professionnel_attente_3'] = $this->seedUser([
            'email' => 'moussa.diop@centre.sn',
            'name' => 'Dr. Moussa Diop',
            'phone' => '+221 77 345 67 89',
            'role' => 'professionnel',
            'status' => 'actif',
            'is_validated' => false,
            'specialite' => 'Pédiatre',
            'matricule' => 'PED-2024-008',
            'centre_de_sante' => 'Centre de Santé de Pikine',
            'dateInscription' => '2025-01-17',
            'rejection_reason' => null,
        ]);

        return $users;
    }

    /**
     * @param array<string, User> $users
     * @return array<string, Grossesse>
     */
    private function seedGrossesses(array $users): array
    {
        $grossesses = [];

        $grossesses['grossesse_fatou'] = $this->seedGrossesse([
            'maman_id' => $users['maman_fatou']->id,
            'date_debut' => '2025-01-05',
            'date_fin_prevue' => '2025-10-12',
            'nombre_grossesses_precedentes' => 0,
            'antecedents_medicaux' => null,
            'professionnel_validateur' => null,
            'date_validation' => null,
            'trimestre' => 1,
            'statut' => 'en_attente',
            'notes' => null,
        ]);

        return $grossesses;
    }

    /**
     * @param array<string, User> $users
     * @param array<string, Grossesse> $grossesses
     * @return array<string, Bebe>
     */
    private function seedBebes(array $users, array $grossesses): array
    {
        $bebes = [];

        $bebes['bebe_moussa'] = $this->seedBebe([
            'maman_id' => $users['maman_fatou']->id,
            'grossesse_id' => $grossesses['grossesse_fatou']->id,
            'nom' => 'Moussa Sall',
            'date_naissance' => '2024-03-15',
            'sexe' => 'M',
            'poids' => 3.2,
            'poids_actuel' => 9.1,
            'taille' => 49,
            'taille_actuelle' => 74,
            'groupe_sanguin' => 'O+',
            'notes' => 'Suivi de croissance conforme.',
        ]);

        $bebes['bebe_mariama'] = $this->seedBebe([
            'maman_id' => $users['maman_fatou']->id,
            'grossesse_id' => $grossesses['grossesse_fatou']->id,
            'nom' => 'Mariama Sall',
            'date_naissance' => '2024-07-20',
            'sexe' => 'F',
            'poids' => 2.5,
            'poids_actuel' => 6.8,
            'taille' => 45,
            'taille_actuelle' => 66,
            'groupe_sanguin' => 'A+',
            'notes' => 'Jumelle, suivi nutritionnel renforcé.',
        ]);

        return $bebes;
    }

    /**
     * @param array<string, User> $users
     */
    private function seedCartes(array $users): void
    {
        $this->seedCarte([
            'maman_id' => $users['maman_fatou']->id,
            'numero_carte' => 'CARTE-2025-0002',
            'date_emission' => '2025-01-10',
            'date_expiration' => '2027-01-10',
            'statut' => 'active',
        ]);
    }

    /**
     * @param array<string, User> $users
     */
    private function seedConsultations(array $users): void
    {
        $this->seedConsultation([
            'maman_id' => $users['maman_fatou']->id,
            'professionnel_id' => $users['professionnel_valide']->id,
            'date' => '2025-04-05',
            'heure' => '09:30:00',
            'type' => 'Consultation prénatale',
            'tension_arterielle' => '12/8',
            'poids' => 68,
            'hauteur_uterine' => null,
            'bcf' => null,
            'semaine_grossesse' => 24,
            'notes' => '3ème consultation prénatale - contrôle routine. Tension stable et bébé en bonne santé.',
        ]);

        $this->seedConsultation([
            'maman_id' => $users['maman_fatou']->id,
            'professionnel_id' => $users['professionnel_valide']->id,
            'date' => '2025-04-20',
            'heure' => '14:00:00',
            'type' => 'Échographie',
            'tension_arterielle' => '11/7',
            'poids' => 68.5,
            'hauteur_uterine' => null,
            'bcf' => null,
            'semaine_grossesse' => 26,
            'notes' => 'Échographie du 3ème trimestre avec croissance normale.',
        ]);

        $this->seedConsultation([
            'maman_id' => $users['maman_fatou']->id,
            'professionnel_id' => $users['professionnel_valide']->id,
            'date' => '2025-04-12',
            'heure' => '18:00:00',
            'type' => "Consultation d'urgence",
            'tension_arterielle' => '12/7',
            'poids' => 61,
            'hauteur_uterine' => null,
            'bcf' => null,
            'semaine_grossesse' => 10,
            'notes' => 'Douleurs abdominales modérées. Repos et surveillance recommandés.',
        ]);
    }

    /**
     * @param array<string, User> $users
     * @param array<string, Grossesse> $grossesses
     */
    private function seedRendezVous(array $users, array $grossesses): void
    {
        $this->seedRendezVousRecord([
            'grossesse_id' => $grossesses['grossesse_fatou']->id,
            'maman_id' => $users['maman_fatou']->id,
            'professionnel_id' => $users['professionnel_valide']->id,
            'date' => '2025-04-05',
            'heure' => '09:30:00',
            'type' => 'Consultation prénatale',
            'motif' => 'Consultation prénatale',
            'lieu' => 'Hôpital Principal de Dakar',
            'statut' => 'prévu',
            'notes' => '3ème consultation prénatale – contrôle routine',
        ]);

        $this->seedRendezVousRecord([
            'grossesse_id' => $grossesses['grossesse_fatou']->id,
            'maman_id' => $users['maman_fatou']->id,
            'professionnel_id' => $users['professionnel_valide']->id,
            'date' => '2025-04-20',
            'heure' => '14:00:00',
            'type' => 'Échographie',
            'motif' => 'Échographie',
            'lieu' => 'Clinique de la Mère et de l\'Enfant',
            'statut' => 'prévu',
            'notes' => 'Échographie du 3ème trimestre',
        ]);
    }

    /**
     * @param array<string, User> $users
     * @param array<string, Bebe> $bebes
     */
    private function seedVaccinations(array $users, array $bebes): void
    {
        $pro = $users['professionnel_valide'];
        $bebe = $bebes['bebe_moussa'];

        $this->seedVaccination([
            'bebe_id' => $bebe->id,
            'nom_vaccin' => 'BCG',
            'age' => 'À la naissance',
            'date_vaccination' => '2024-03-15',
            'prochaine_dose' => null,
            'notes' => 'Administration à la maternité',
            'professionnel_id' => $pro->id,
        ]);

        $this->seedVaccination([
            'bebe_id' => $bebe->id,
            'nom_vaccin' => 'Hépatite B (1ère dose)',
            'age' => 'À la naissance',
            'date_vaccination' => '2024-03-15',
            'prochaine_dose' => null,
            'notes' => '',
            'professionnel_id' => $pro->id,
        ]);

        $this->seedVaccination([
            'bebe_id' => $bebe->id,
            'nom_vaccin' => 'Pentavalent (1ère dose)',
            'age' => '6 semaines',
            'date_vaccination' => '2024-04-26',
            'prochaine_dose' => null,
            'notes' => '',
            'professionnel_id' => $pro->id,
        ]);

        $this->seedVaccination([
            'bebe_id' => $bebe->id,
            'nom_vaccin' => 'VPO (1ère dose)',
            'age' => '6 semaines',
            'date_vaccination' => '2024-04-26',
            'prochaine_dose' => null,
            'notes' => '',
            'professionnel_id' => $pro->id,
        ]);

        $this->seedVaccination([
            'bebe_id' => $bebe->id,
            'nom_vaccin' => 'Pentavalent (2ème dose)',
            'age' => '10 semaines',
            'date_vaccination' => '2024-05-24',
            'prochaine_dose' => null,
            'notes' => '',
            'professionnel_id' => $pro->id,
        ]);

        $this->seedVaccination([
            'bebe_id' => $bebe->id,
            'nom_vaccin' => 'Pentavalent (3ème dose)',
            'age' => '14 semaines',
            'date_vaccination' => '2024-06-21',
            'prochaine_dose' => null,
            'notes' => '',
            'professionnel_id' => $pro->id,
        ]);

        $this->seedVaccination([
            'bebe_id' => $bebe->id,
            'nom_vaccin' => 'ROR (Rougeole-Oreillons-Rubéole)',
            'age' => '9 mois',
            'date_vaccination' => '2024-12-15',
            'prochaine_dose' => null,
            'notes' => '',
            'professionnel_id' => $pro->id,
        ]);

        $this->seedVaccination([
            'bebe_id' => $bebe->id,
            'nom_vaccin' => 'Fièvre Jaune',
            'age' => '9 mois',
            'date_vaccination' => '2024-12-15',
            'prochaine_dose' => null,
            'notes' => '',
            'professionnel_id' => $pro->id,
        ]);

        $this->seedVaccination([
            'bebe_id' => $bebe->id,
            'nom_vaccin' => 'Méningite A',
            'age' => '12 mois',
            'date_vaccination' => '2025-03-15',
            'prochaine_dose' => null,
            'notes' => '',
            'professionnel_id' => null,
        ]);

        $this->seedVaccination([
            'bebe_id' => $bebe->id,
            'nom_vaccin' => 'Pneumocoque',
            'age' => '24 mois',
            'date_vaccination' => '2026-09-15',
            'prochaine_dose' => null,
            'notes' => '',
            'professionnel_id' => null,
        ]);

        $this->seedVaccination([
            'bebe_id' => $bebe->id,
            'nom_vaccin' => 'Rappel DTC + Polio',
            'age' => '18 mois',
            'date_vaccination' => '2027-03-15',
            'prochaine_dose' => null,
            'notes' => '',
            'professionnel_id' => null,
        ]);
    }

    /**
     * @param array<string, Bebe> $bebes
     */
    private function seedScans(array $bebes): void
    {
        $this->seedScan([
            'bebe_id' => $bebes['bebe_moussa']->id,
            'type_scan' => 'Echographie morphologique',
            'date_scan' => '2024-12-12',
            'resultat' => 'Croissance conforme, aucun signe d anomalie detecte.',
            'notes' => 'Suivi morphologique de deuxième trimestre.',
        ]);

        $this->seedScan([
            'bebe_id' => $bebes['bebe_mariama']->id,
            'type_scan' => 'Echographie de controle',
            'date_scan' => '2023-07-20',
            'resultat' => 'Evolution normale, poids estimatif rassurant.',
            'notes' => 'Contrôle de routine après consultation.',
        ]);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function seedUser(array $data): User
    {
        $lookup = [];

        if (! empty($data['email'])) {
            $lookup['email'] = $data['email'];
        } elseif (! empty($data['phone'])) {
            $lookup['phone'] = $data['phone'];
        } else {
            $lookup['name'] = $data['name'];
        }

        $user = User::updateOrCreate(
            $lookup,
            [
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'],
                'password' => Hash::make('demo1234'),
                'role' => $data['role'],
                'status' => $data['status'],
                'is_validated' => $data['is_validated'],
                'specialite' => $data['specialite'] ?? null,
                'matricule' => $data['matricule'] ?? null,
                'centre_de_sante' => $data['centre_de_sante'] ?? null,
                'rejection_reason' => $data['rejection_reason'] ?? null,
            ]
        );

        $this->syncTimestamp($user, $data['dateInscription']);

        return $user->refresh();
    }

    /**
     * @param array<string, mixed> $data
     */
    private function seedGrossesse(array $data): Grossesse
    {
        $grossesse = Grossesse::updateOrCreate(
            [
                'maman_id' => $data['maman_id'],
                'date_debut' => $data['date_debut'],
            ],
            [
                'date_fin_prevue' => $data['date_fin_prevue'],
                'nombre_grossesses_precedentes' => $data['nombre_grossesses_precedentes'],
                'antecedents_medicaux' => $data['antecedents_medicaux'],
                'professionnel_validateur' => $data['professionnel_validateur'],
                'date_validation' => $data['date_validation'],
                'trimestre' => $data['trimestre'],
                'statut' => $data['statut'],
                'notes' => $data['notes'],
            ]
        );

        $this->syncTimestamp($grossesse, $data['date_debut']);

        return $grossesse->refresh();
    }

    /**
     * @param array<string, mixed> $data
     */
    private function seedBebe(array $data): Bebe
    {
        $bebe = Bebe::updateOrCreate(
            [
                'maman_id' => $data['maman_id'],
                'nom' => $data['nom'],
                'date_naissance' => $data['date_naissance'],
            ],
            [
                'grossesse_id' => $data['grossesse_id'],
                'sexe' => $data['sexe'],
                'poids' => $data['poids'],
                'poids_actuel' => $data['poids_actuel'],
                'taille' => $data['taille'],
                'taille_actuelle' => $data['taille_actuelle'],
                'groupe_sanguin' => $data['groupe_sanguin'],
                'notes' => $data['notes'],
            ]
        );

        $this->syncTimestamp($bebe, $data['date_naissance']);

        return $bebe->refresh();
    }

    /**
     * @param array<string, mixed> $data
     */
    private function seedCarte(array $data): Carte
    {
        $carte = Carte::updateOrCreate(
            ['numero_carte' => $data['numero_carte']],
            [
                'maman_id' => $data['maman_id'],
                'date_emission' => $data['date_emission'],
                'date_expiration' => $data['date_expiration'],
                'statut' => $data['statut'],
            ]
        );

        $this->syncTimestamp($carte, $data['date_emission']);

        return $carte->refresh();
    }

    /**
     * @param array<string, mixed> $data
     */
    private function seedConsultation(array $data): Consultation
    {
        $consultation = Consultation::updateOrCreate(
            [
                'maman_id' => $data['maman_id'],
                'professionnel_id' => $data['professionnel_id'],
                'date' => $data['date'],
                'heure' => $data['heure'],
            ],
            [
                'type' => $data['type'],
                'tension_arterielle' => $data['tension_arterielle'],
                'poids' => $data['poids'],
                'hauteur_uterine' => $data['hauteur_uterine'],
                'bcf' => $data['bcf'],
                'semaine_grossesse' => $data['semaine_grossesse'],
                'notes' => $data['notes'],
            ]
        );

        $this->syncTimestamp($consultation, $data['date']);

        return $consultation->refresh();
    }

    /**
     * @param array<string, mixed> $data
     */
    private function seedRendezVousRecord(array $data): RendezVous
    {
        $rendezVous = RendezVous::updateOrCreate(
            [
                'maman_id' => $data['maman_id'],
                'professionnel_id' => $data['professionnel_id'],
                'grossesse_id' => $data['grossesse_id'],
                'date' => $data['date'],
                'heure' => $data['heure'],
            ],
            [
                'type' => $data['type'],
                'motif' => $data['motif'],
                'lieu' => $data['lieu'],
                'statut' => $data['statut'],
                'notes' => $data['notes'],
            ]
        );

        $this->syncTimestamp($rendezVous, $data['date']);

        return $rendezVous->refresh();
    }

    /**
     * @param array<string, mixed> $data
     */
    private function seedVaccination(array $data): Vaccination
    {
        $vaccination = Vaccination::updateOrCreate(
            [
                'bebe_id' => $data['bebe_id'],
                'nom_vaccin' => $data['nom_vaccin'],
                'date_vaccination' => $data['date_vaccination'],
            ],
            [
                'age' => $data['age'],
                'prochaine_dose' => $data['prochaine_dose'],
                'notes' => $data['notes'],
                'professionnel_id' => $data['professionnel_id'],
            ]
        );

        $this->syncTimestamp($vaccination, $data['date_vaccination']);

        return $vaccination->refresh();
    }

    /**
     * @param array<string, mixed> $data
     */
    private function seedScan(array $data): Scan
    {
        $scan = Scan::updateOrCreate(
            [
                'bebe_id' => $data['bebe_id'],
                'type_scan' => $data['type_scan'],
                'date_scan' => $data['date_scan'],
            ],
            [
                'resultat' => $data['resultat'],
                'notes' => $data['notes'],
            ]
        );

        $this->syncTimestamp($scan, $data['date_scan']);

        return $scan->refresh();
    }

    private function syncTimestamp(Model $model, string $date): void
    {
        $timestamp = Carbon::parse($date)->startOfDay();

        $model->timestamps = false;
        $model->forceFill([
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);
        $model->saveQuietly();
        $model->timestamps = true;
    }
}
