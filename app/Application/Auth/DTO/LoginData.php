<?php

namespace App\Application\Auth\DTO;

readonly class LoginData
{
    public function __construct(
        public ?string $login,
        public string $password,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $login = isset($data['login']) ? (string) $data['login'] : (isset($data['loginId']) ? (string) $data['loginId'] : (isset($data['email']) ? (string) $data['email'] : null));

        return new self(
            login: $login !== null ? trim($login) : null,
            password: (string) $data['password'],
        );
    }
}
