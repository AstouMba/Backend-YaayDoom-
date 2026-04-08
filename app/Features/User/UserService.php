<?php

namespace App\Features\User;

use App\Application\User\CreateUser;
use App\Application\User\DeleteUser;
use App\Application\User\DTO\CreateUserData;
use App\Application\User\DTO\UpdateUserData;
use App\Application\User\FindUserByEmail;
use App\Application\User\FindUsersByRole;
use App\Application\User\GetUser;
use App\Application\User\ListUsers;
use App\Application\User\UpdateUser;
use App\Models\User;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;

class UserService extends Service
{
    public function __construct(
        private CreateUser $createUser,
        private GetUser $getUser,
        private ListUsers $listUsers,
        private UpdateUser $updateUser,
        private DeleteUser $deleteUser,
        private FindUserByEmail $findUserByEmail,
        private FindUsersByRole $findUsersByRole,
    ) {}

    /**
     * Créer un nouvel utilisateur
     */
    public function create(array $data): User
    {
        return $this->createUser->execute(CreateUserData::fromArray($data));
    }

    /**
     * Récupérer un utilisateur par ID
     */
    public function get(string $id): ?User
    {
        return $this->getUser->execute($id);
    }

    /**
     * Récupérer tous les utilisateurs
     */
    public function getAll(): Collection
    {
        return $this->listUsers->execute();
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(string $id, array $data): ?User
    {
        return $this->updateUser->execute($id, UpdateUserData::fromArray($data));
    }

    /**
     * Supprimer un utilisateur
     */
    public function delete(string $id): bool
    {
        return $this->deleteUser->execute($id);
    }

    /**
     * Rechercher par email
     */
    public function findByEmail(string $email): ?User
    {
        return $this->findUserByEmail->execute($email);
    }

    /**
     * Rechercher par rôle
     */
    public function findByRole(string $role): Collection
    {
        return $this->findUsersByRole->execute($role);
    }
}
