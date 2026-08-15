<?php

namespace App\Infrastructure\Categories\Persistence\Eloquent\Repositories;

use App\Domain\Categories\Entities\Category;
use App\Domain\Categories\Repositories\CategoryRepositoryInterface;
use App\Infrastructure\Categories\Persistence\Eloquent\Models\CategoryModel;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    protected function mapToDomain(CategoryModel $model): Category
    {
        return new Category(
            id: $model->id,
            name: $model->name,
            createdAt: $model->created_at?->toDateTimeString(),
            updatedAt: $model->updated_at?->toDateTimeString()
        );
    }

    /**
     * @return Category[]
     */
    public function all(): array
    {
        return CategoryModel::all()->map(fn($model) => $this->mapToDomain($model))->toArray();
    }

    public function findById(string $id): ?Category
    {
        $model = CategoryModel::find($id);
        return $model ? $this->mapToDomain($model) : null;
    }

    public function create(Category $category): Category
    {
        $model = new CategoryModel();
        $model->name = $category->name;
        $model->save();
        return $this->mapToDomain($model);
    }

    public function update(string $id, Category $category): ?Category
    {
        $model = CategoryModel::find($id);
        if (!$model) {
            return null;
        }
        $model->name = $category->name;
        $model->save();
        return $this->mapToDomain($model);
    }

    public function delete(string $id): bool
    {
        $model = CategoryModel::find($id);
        if (!$model) {
            return false;
        }
        return (bool) $model->delete();
    }

    public function existsWithName(string $name, ?string $exceptId = null): bool
    {
        $query = CategoryModel::where('name', $name);
        if ($exceptId !== null) {
            $query->where('id', '!=', $exceptId);
        }
        return $query->exists();
    }

    public function hasAssociatedProducts(string $categoryId): bool
    {
        $model = CategoryModel::find($categoryId);
        return $model ? $model->products()->exists() : false;
    }

    public function count(): int
    {
        return CategoryModel::count();
    }

    public function paginate(int $perPage = 10, int $page = 1): array
    {
        $paginator = CategoryModel::paginate($perPage, ['*'], 'page', $page);
        
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
