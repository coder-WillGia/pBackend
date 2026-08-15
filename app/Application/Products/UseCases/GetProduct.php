<?php

namespace App\Application\Products\UseCases;

use App\Domain\Products\Entities\Product;
use App\Domain\Products\Repositories\ProductRepositoryInterface;

class GetProduct
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function execute(int $id): ?Product
    {
        return $this->productRepository->findById($id);
    }
}
