<?php

namespace App\Application\Product\UseCases;

use App\Domain\Product\Repositories\ProductRepositoryInterface;

class ListProductsUseCase
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function execute(): array
    {
        return $this->productRepository->all();
    }
}
