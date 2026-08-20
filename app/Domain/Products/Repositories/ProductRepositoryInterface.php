<?php

namespace App\Domain\Products\Repositories;

use App\Domain\Products\Entities\Product;

interface ProductRepositoryInterface
{
    /**
     * @return Product[]
     */
    public function all(): array;

    public function findById(string $id): ?Product;

    public function create(Product $product): Product;

    public function update(string $id, Product $product): ?Product;

    public function delete(string $id): bool;

    public function count(): int;

    public function sumStock(): int;

    public function getDashboardMetrics(): array;

    public function latest(int $limit = 5): array;

    public function paginate(int $perPage = 10, int $page = 1): array;
}
