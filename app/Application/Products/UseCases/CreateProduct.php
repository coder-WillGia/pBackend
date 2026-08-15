<?php

namespace App\Application\Products\UseCases;

use App\Domain\Products\Entities\Product;
use App\Domain\Products\Repositories\ProductRepositoryInterface;
use App\Domain\Categories\Repositories\CategoryRepositoryInterface;

class CreateProduct
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
