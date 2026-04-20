<?php

namespace App\Features\Auth;

use App\Application\Auth\ChangePassword;
use App\Application\Auth\DTO\ChangePasswordData;
use App\Application\Auth\DTO\LoginData;
use App\Application\Auth\DTO\RegisterData;
use App\Application\Auth\DTO\UpdateProfileData;
use App\Application\Auth\GetCurrentUser;
use App\Application\Auth\IssuePassportToken;
use App\Application\Auth\LoginUser;
use App\Application\Auth\LogoutUser;
use App\Application\Auth\RegisterUser;
use App\Application\Auth\UploadProfessionalDocuments;
use App\Application\Auth\UpdateProfile;
use App\Models\User;
use App\Services\Service;

class AuthService extends Service
{
    public function __construct(
        private RegisterUser $registerUser,
        private LoginUser $loginUser,
        private LogoutUser $logoutUser,
        private GetCurrentUser $getCurrentUser,
        private IssuePassportToken $issuePassportToken,
        private UpdateProfile $updateProfile,
        private ChangePassword $changePasswordUseCase,
        private UploadProfessionalDocuments $uploadProfessionalDocumentsUseCase,
    ) {}

    /**
     * Inscrire un nouvel utilisateur
     */
    public function register(array $data): User
    {
        return $this->registerUser->execute(RegisterData::fromArray($data));
    }

    /**
     * Générer un token Passport robuste, même si le client personnel manque.
     */
    public function issueToken(User $user, string $tokenName = 'auth_token'): string
    {
        return $this->issuePassportToken->execute($user, $tokenName);
    }

    /**
     * Connecter un utilisateur via email ou téléphone
     */
    public function login(array $data): array
    {
        return $this->loginUser->execute(LoginData::fromArray($data));
    }

    /**
     * Déconnecter un utilisateur
     */
    public function logout(): void
    {
        $this->logoutUser->execute();
    }

    /**
     * Récupérer l'utilisateur connecté
     */
    public function me(): ?User
    {
        return $this->getCurrentUser->execute();
    }

    /**
     * Mettre à jour le profil de l'utilisateur connecté
     */
    public function updateMe(array $data, ?User $user = null): User
    {
        return $this->updateProfile->execute(UpdateProfileData::fromArray($data), $user);
    }

    /**
     * Changer le mot de passe de l'utilisateur connecté
     */
    public function changePassword(array $data, ?User $user = null): void
    {
        $this->changePasswordUseCase->execute(ChangePasswordData::fromArray($data), $user);
    }

    /**
     * Ajouter les documents de vérification d'un professionnel.
     *
     * @param array<int, \Illuminate\Http\UploadedFile> $documents
     */
    public function uploadProfessionalDocuments(array $documents, ?User $user = null): User
    {
        return $this->uploadProfessionalDocumentsUseCase->execute($documents, $user);
    }
}
