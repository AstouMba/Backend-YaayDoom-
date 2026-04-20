<?php

namespace App\Application\Auth\DTO;

readonly class LoginData
{
    public function __construct(
        public ?string $email,
        public ?string $phone,
        public string $password,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $email = self::normalizeString($data['email'] ?? null);
        $phone = self::normalizeString($data['phone'] ?? $data['telephone'] ?? $data['tel'] ?? null);
        $legacyIdentifier = self::normalizeString($data['loginId'] ?? $data['identifier'] ?? $data['identifiant'] ?? null);

        if ($email === null && $phone === null && $legacyIdentifier !== null) {
            if (filter_var($legacyIdentifier, FILTER_VALIDATE_EMAIL)) {
                $email = $legacyIdentifier;
            } else {
                $phone = $legacyIdentifier;
            }
        }

        return new self(
            email: $email,
            phone: $phone,
            password: (string) $data['password'],
        );
    }

    private static function normalizeString(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $value = trim($value);

        return $value === '' ? null : $value;
    }
}
