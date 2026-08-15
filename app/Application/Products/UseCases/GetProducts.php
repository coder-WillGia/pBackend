<?php

namespace App\Application\Products\UseCases;

use App\Domain\Products\Repositories\ProductRepositoryInterface;

class GetProducts
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function execute(?int $perPage = null, int $page = 1): array
    {
        if ($perPage !== null) {
            return $this->productRepository->paginate($perPage, $page);
        }
        return $this->productRepository->all();
    }
}
