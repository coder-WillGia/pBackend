<?php

namespace App\Infrastructure\Products\Persistence\Eloquent\Repositories;

use App\Domain\Products\Entities\Product;
use App\Domain\Products\Repositories\ProductRepositoryInterface;
use App\Infrastructure\Products\Persistence\Eloquent\Models\ProductModel;
use Illuminate\Support\Facades\DB;

class EloquentProductRepository implements ProductRepositoryInterface
{
    protected function mapToDomain(ProductModel $model): Product
    {
        return new Product(
            id: $model->id,
            name: $model->name,
            description: $model->description,
            price: $model->price,
            stock: $model->stock,
            categoryId: $model->category_id,
            createdAt: $model->created_at?->toDateTimeString(),
            updatedAt: $model->updated_at?->toDateTimeString()
        );
    }

    /**
     * @return Product[]
     */
    public function all(): array
    {
        return ProductModel::all()->map(fn($model) => $this->mapToDomain($model))->toArray();
    }

    public function findById(string $id): ?Product
    {
        $model = ProductModel::find($id);
        return $model ? $this->mapToDomain($model) : null;
    }

    public function create(Product $product): Product
    {
        $model = new ProductModel();
        $model->name = $product->name;
        $model->description = $product->description;
        $model->price = $product->price;
        $model->stock = $product->stock;
        $model->category_id = $product->categoryId;
        $model->save();
        return $this->mapToDomain($model);
    }

    public function update(string $id, Product $product): ?Product
    {
        $model = ProductModel::find($id);
        if (!$model) {
            return null;
        }
        $model->name = $product->name;
        $model->description = $product->description;
        $model->price = $product->price;
        $model->stock = $product->stock;
        $model->category_id = $product->categoryId;
        $model->save();
        return $this->mapToDomain($model);
    }

    public function delete(string $id): bool
    {
        $model = ProductModel::find($id);
        if (!$model) {
            return false;
        }
        return (bool) $model->delete();
    }

    public function count(): int
    {
        return ProductModel::count();
    }

    public function sumStock(): int
    {
        return (int) ProductModel::sum('stock');
    }

    public function getDashboardMetrics(): array
    {
        $result = DB::selectOne("
            SELECT 
                (SELECT COUNT(*) FROM categories) AS total_categories,
                (SELECT COUNT(*) FROM products)   AS total_products,
                (SELECT COALESCE(SUM(stock), 0) FROM products) AS total_stock
        ");

        return [
            'total_categories' => (int) ($result->total_categories ?? 0),
            'total_products'   => (int) ($result->total_products ?? 0),
            'total_stock'      => (int) ($result->total_stock ?? 0),
        ];
    }

    public function latest(int $limit = 5): array
    {
        return DB::table('products')
            ->select(['id', 'name', 'description', 'price', 'stock', 'category_id', 'created_at', 'updated_at'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(fn($row) => new Product(
                id: (string) $row->id,
                name: $row->name,
                description: $row->description,
                price: (float) $row->price,
                stock: (int) $row->stock,
                categoryId: (string) $row->category_id,
                createdAt: $row->created_at ? (string) $row->created_at : null,
                updatedAt: $row->updated_at ? (string) $row->updated_at : null
            ))
            ->toArray();
    }

    public function paginate(int $perPage = 10, int $page = 1): array
    {
        $paginator = ProductModel::paginate($perPage, ['*'], 'page', $page);
        
        return [
            'items' => collect($paginator->items())->map(fn($model) => $this->mapToDomain($model))->toArray(),
            'meta' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
            ]
        ];
    }
}
