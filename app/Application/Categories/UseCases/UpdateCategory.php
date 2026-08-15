<?php

namespace App\Application\Categories\UseCases;

use App\Domain\Categories\Entities\Category;
use App\Domain\Categories\Repositories\CategoryRepositoryInterface;

class UpdateCategory
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    public function execute(int $id, string $name): ?Category
    {
        if ($this->categoryRepository->existsWithName($name, $id)) {
            throw new \InvalidArgumentException("La categoría con este nombre ya existe.");
        }

        $category = new Category($id, $name);
        return $this->categoryRepository->update($id, $category);
    }
}
