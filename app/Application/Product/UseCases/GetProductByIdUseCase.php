<?php

namespace App\Application\Product\UseCases;

use App\Domain\Product\Entities\Product;
use App\Domain\Product\Repositories\ProductRepositoryInterface;

class GetProductByIdUseCase
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function execute(int $id): ?Product
    {
        return $this->productRepository->findById($id);
    }
}
