<?php

namespace App\Application\Category\UseCases;

use App\Domain\Category\Repositories\CategoryRepositoryInterface;

class ListCategoriesUseCase
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    public function execute(): array
    {
        return $this->categoryRepository->all();
    }
}
