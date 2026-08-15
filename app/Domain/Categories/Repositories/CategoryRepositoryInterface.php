<?php

namespace App\Domain\Categories\Repositories;

use App\Domain\Categories\Entities\Category;

interface CategoryRepositoryInterface
{
    /**
     * @return Category[]
     */
    public function all(): array;

    public function findById(string $id): ?Category;

    public function create(Category $category): Category;

    public function update(string $id, Category $category): ?Category;

    public function delete(string $id): bool;

    public function existsWithName(string $name, ?string $exceptId = null): bool;

    public function hasAssociatedProducts(string $categoryId): bool;
}
