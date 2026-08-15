<?php

namespace App\Application\Categories\UseCases;

use App\Domain\Categories\Repositories\CategoryRepositoryInterface;

class GetCategories
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    public function execute(?int $perPage = null, int $page = 1): array
    {
        if ($perPage !== null) {
            return $this->categoryRepository->paginate($perPage, $page);
        }
        return $this->categoryRepository->all();
    }
}
