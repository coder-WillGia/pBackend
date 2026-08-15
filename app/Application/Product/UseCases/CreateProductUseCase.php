<?php

namespace App\Application\Product\UseCases;

use App\Domain\Product\Entities\Product;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Domain\Category\Repositories\CategoryRepositoryInterface;

class CreateProductUseCase
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    public function execute(string $name, ?string $description, float $price, int $stock, int $categoryId): Product
    {
        $category = $this->categoryRepository->findById($categoryId);
        if (!$category) {
            throw new \InvalidArgumentException("La categoría especificada no existe.");
        }

        $product = new Product(null, $name, $description, $price, $stock, $categoryId);
        return $this->productRepository->create($product);
    }
}
