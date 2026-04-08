<?php

namespace App\Application\Admin\DTO;

readonly class UserFiltersData
{
    public function __construct(
        public ?string $role,
        public ?string $status,
        public ?string $search,
        public int $page,
        public int $perPage,
    ) {}

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            role: isset($data['role']) ? (string) $data['role'] : null,
            status: isset($data['status']) ? (string) $data['status'] : null,
            search: isset($data['search']) ? (string) $data['search'] : null,
            page: max(1, (int) ($data['page'] ?? 1)),
            perPage: max(1, (int) ($data['per_page'] ?? 10)),
        );
    }
}
