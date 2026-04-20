<?php

namespace App\Features\User;

use App\Models\User;

class UserPresenter
{
    /**
     * Format AuthUser exposé au frontend.
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
            'name' => $user->name,
            'email' => $user->email,
            'telephone' => $user->phone,
            'phone' => $user->phone,
            'date_naissance' => $user->birth_date?->format('Y-m-d'),
            'role' => $user->role,
            'isValidated' => (bool) $user->is_validated,
            'specialite' => $user->specialite,
            'matricule' => $user->matricule,
            'centreDeSante' => $user->centre_de_sante,
            'documentUrl' => $verificationDocuments[0]['url'] ?? null,
            'documents' => $verificationDocuments,
            'decisionStatus' => $user->decision_status,
            'decisionMotif' => $user->decision_motif,
            'decisionDate' => $user->decision_date?->format('Y-m-d'),
            'decisionBy' => $user->decision_by,
            'statut' => $user->status,
            'dateInscription' => $user->created_at?->format('Y-m-d'),
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
            'documents' => $verificationDocuments,
            'documentUrl' => $verificationDocuments[0]['url'] ?? null,
            'decisionStatus' => $user->decision_status,
            'decisionMotif' => $user->decision_motif,
            'decisionDate' => $user->decision_date?->format('Y-m-d H:i:s'),
            'decisionBy' => $user->decision_by,
            'isValidated' => (bool) $user->is_validated,
            'statut' => $user->status,
            'dateInscription' => $user->created_at?->format('Y-m-d'),
        ];
    }
}
