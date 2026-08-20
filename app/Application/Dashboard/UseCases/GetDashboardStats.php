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
        $metrics = $this->productRepository->getDashboardMetrics();

        return [
            'total_categories' => $metrics['total_categories'],
            'total_products'   => $metrics['total_products'],
            'total_stock'      => $metrics['total_stock'],
            'latest_products'  => $this->productRepository->latest($limit),
        ];
    }
}
