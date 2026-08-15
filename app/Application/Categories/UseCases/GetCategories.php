<?php

namespace App\Application\Categories\UseCases;

use App\Domain\Categories\Repositories\CategoryRepositoryInterface;

class GetCategories
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    public function execute(): array
    {
        return $this->categoryRepository->all();
    }
}
