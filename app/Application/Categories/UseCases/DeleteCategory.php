<?php

namespace App\Application\Categories\UseCases;

use App\Domain\Categories\Repositories\CategoryRepositoryInterface;

class DeleteCategory
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    public function execute(string $id): bool
    {
        $category = $this->categoryRepository->findById($id);
        if (!$category) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Categoría no encontrada.");
        }

        if ($this->categoryRepository->hasAssociatedProducts($id)) {
            throw new \InvalidArgumentException("No se puede eliminar la categoría porque tiene productos asociados.");
        }

        return $this->categoryRepository->delete($id);
    }
}
