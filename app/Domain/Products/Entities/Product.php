<?php

namespace App\Domain\Products\Entities;

class Product
{
    public function __construct(
        public ?int $id,
        public string $name,
        public ?string $description,
        public float $price,
        public int $stock,
        public int $categoryId,
        public ?string $createdAt = null,
        public ?string $updatedAt = null
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'category_id' => $this->categoryId,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
