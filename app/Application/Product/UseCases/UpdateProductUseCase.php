<?php

namespace App\Application\Product\UseCases;

use App\Domain\Product\Entities\Product;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;

class UpdateProductUseCase
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    public function execute(int $id, string $name, ?string $description, float $price, int $stock, int $categoryId): ?Product
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
