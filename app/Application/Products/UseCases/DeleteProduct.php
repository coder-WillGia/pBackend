<?php

namespace App\Application\Products\UseCases;

use App\Domain\Products\Repositories\ProductRepositoryInterface;

class DeleteProduct
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function execute(string $id): bool
    {
        $product = $this->productRepository->findById($id);
        if (!$product) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Producto no encontrado.");
        }

        return $this->productRepository->delete($id);
    }
}
