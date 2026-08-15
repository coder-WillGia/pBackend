<?php

namespace App\Application\Category\UseCases;

use App\Domain\Category\Entities\Category;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;

class CreateCategoryUseCase
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    public function execute(string $name): Category
    {
        if ($this->categoryRepository->existsWithName($name)) {
            throw new \InvalidArgumentException("La categoría con este nombre ya existe.");
        }

        $category = new Category(null, $name);
        return $this->categoryRepository->create($category);
    }
}
