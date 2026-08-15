<?php

namespace App\Domain\Categories\Repositories;

use App\Domain\Categories\Entities\Category;

interface CategoryRepositoryInterface
{
    /**
     * @return Category[]
     */
    public function all(): array;

    public function findById(int $id): ?Category;

    public function create(Category $category): Category;

    public function update(int $id, Category $category): ?Category;

    public function delete(int $id): bool;

    public function existsWithName(string $name, ?int $exceptId = null): bool;

    public function hasAssociatedProducts(int $categoryId): bool;
}
