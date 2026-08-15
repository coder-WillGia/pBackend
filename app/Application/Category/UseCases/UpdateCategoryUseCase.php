<?php

namespace App\Application\Category\UseCases;

use App\Domain\Category\Entities\Category;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;

class UpdateCategoryUseCase
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
