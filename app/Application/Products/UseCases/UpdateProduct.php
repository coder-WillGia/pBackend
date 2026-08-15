<?php

namespace App\Application\Products\UseCases;

use App\Domain\Products\Entities\Product;
use App\Domain\Products\Repositories\ProductRepositoryInterface;
use App\Domain\Categories\Repositories\CategoryRepositoryInterface;

class UpdateProduct
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    public function execute(string $id, string $name, ?string $description, float $price, int $stock, string $categoryId): ?Product
    {
        $product = $this->productRepository->findById($id);
        if (!$product) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException("Producto no encontrado.");
        }

        $category = $this->categoryRepository->findById($categoryId);
        if (!$category) {
            throw new \InvalidArgumentException("La categoría especificada no existe.");
        }

        $updatedProduct = new Product($id, $name, $description, $price, $stock, $categoryId);
        return $this->productRepository->update($id, $updatedProduct);
    }
}
