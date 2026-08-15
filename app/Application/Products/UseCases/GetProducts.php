<?php

namespace App\Application\Products\UseCases;

use App\Domain\Products\Repositories\ProductRepositoryInterface;

class GetProducts
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function execute(): array
    {
        return $this->productRepository->all();
    }
}
