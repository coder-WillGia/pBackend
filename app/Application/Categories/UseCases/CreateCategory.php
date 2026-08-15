<?php

namespace App\Application\Categories\UseCases;

use App\Domain\Categories\Entities\Category;
use App\Domain\Categories\Repositories\CategoryRepositoryInterface;

class CreateCategory
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
