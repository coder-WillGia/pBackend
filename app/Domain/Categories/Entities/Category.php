<?php

namespace App\Domain\Categories\Entities;

class Category
{
    public function __construct(
        public ?int $id,
        public string $name,
        public ?string $createdAt = null,
        public ?string $updatedAt = null
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
