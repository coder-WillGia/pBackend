<?php

namespace App\Infrastructure\Products\Persistence\Eloquent\Repositories;

use App\Domain\Products\Entities\Product;
use App\Domain\Products\Repositories\ProductRepositoryInterface;
use App\Infrastructure\Products\Persistence\Eloquent\Models\ProductModel;

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

    public function latest(int $limit = 5): array
    {
        return ProductModel::orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(fn($model) => $this->mapToDomain($model))
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
