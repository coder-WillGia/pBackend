<?php

namespace App\Application\Category\UseCases;

use App\Domain\Category\Entities\Category;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;

class GetCategoryByIdUseCase
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    public function execute(int $id): ?Category
    {
        return $this->categoryRepository->findById($id);
    }
}
