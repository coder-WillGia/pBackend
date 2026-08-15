<?php

namespace App\Application\Categories\UseCases;

use App\Domain\Categories\Entities\Category;
use App\Domain\Categories\Repositories\CategoryRepositoryInterface;

class GetCategory
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    public function execute(string $id): ?Category
    {
        return $this->categoryRepository->findById($id);
    }
}
