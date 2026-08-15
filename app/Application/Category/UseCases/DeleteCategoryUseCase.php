<?php

namespace App\Application\Category\UseCases;

use App\Domain\Category\Repositories\CategoryRepositoryInterface;

class DeleteCategoryUseCase
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    public function execute(int $id): bool
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
