<?php

namespace App\Application\Dashboard\UseCases;

use App\Domain\Categories\Repositories\CategoryRepositoryInterface;
use App\Domain\Products\Repositories\ProductRepositoryInterface;

class GetDashboardStats
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository,
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function execute(int $limit = 5): array
    {
        return [
            'total_categories' => $this->categoryRepository->count(),
            'total_products' => $this->productRepository->count(),
            'total_stock' => $this->productRepository->sumStock(),
            'latest_products' => $this->productRepository->latest($limit),
        ];
    }
}
