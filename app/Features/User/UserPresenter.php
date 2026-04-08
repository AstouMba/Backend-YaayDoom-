<?php

namespace App\Features\User;

use App\Models\User;

class UserPresenter
{
    /**
     * Format standard exposé au frontend.
     *
     * @return array<string, mixed>
     */
    public static function contract(User $user): array
    {
        $verificationDocuments = is_array($user->verification_documents ?? null)
            ? $user->verification_documents
            : [];

        return [
            'id' => $user->id,
            'nom' => $user->name,
            'email' => $user->email,
            'telephone' => $user->phone,
            'date_naissance' => optional($user->birth_date)?->format('Y-m-d'),
            'birthDate' => optional($user->birth_date)?->format('Y-m-d'),
            'role' => $user->role,
            'specialite' => $user->specialite,
            'matricule' => $user->matricule,
            'centre_de_sante' => $user->centre_de_sante,
            'centreDesante' => $user->centre_de_sante,
            'verification_documents' => $verificationDocuments,
            'documents' => $verificationDocuments,
            'documentUrl' => $verificationDocuments[0]['url'] ?? null,
            'decisionStatus' => $user->decision_status,
            'decisionMotif' => $user->decision_motif,
            'decisionDate' => optional($user->decision_date)?->format('Y-m-d H:i:s'),
            'decisionBy' => $user->decision_by,
            'is_validated' => (bool) $user->is_validated,
            'isValidated' => (bool) $user->is_validated,
            'statut' => $user->status,
            'status' => $user->status,
            'date_inscription' => optional($user->created_at)?->format('Y-m-d'),
            'dateInscription' => optional($user->created_at)?->format('Y-m-d'),
        ];
    }

    /**
     * Format attendu par les écrans admin.
     *
     * @return array<string, mixed>
     */
    public static function admin(User $user): array
    {
        $verificationDocuments = is_array($user->verification_documents ?? null)
            ? $user->verification_documents
            : [];

        return [
            'id' => $user->id,
            'nom' => $user->name,
            'email' => $user->email,
            'telephone' => $user->phone,
            'role' => $user->role,
            'specialite' => $user->specialite,
            'matricule' => $user->matricule,
            'centreDesante' => $user->centre_de_sante,
            'verification_documents' => $verificationDocuments,
            'documents' => $verificationDocuments,
            'documentUrl' => $verificationDocuments[0]['url'] ?? null,
            'decisionStatus' => $user->decision_status,
            'decisionMotif' => $user->decision_motif,
            'decisionDate' => optional($user->decision_date)?->format('Y-m-d H:i:s'),
            'decisionBy' => $user->decision_by,
            'isValidated' => (bool) $user->is_validated,
            'statut' => $user->status,
            'motifRejet' => $user->rejection_reason,
            'dateInscription' => optional($user->created_at)?->format('Y-m-d'),
        ];
    }
}
