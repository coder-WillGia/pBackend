<?php

namespace App\Infrastructure\Categories\Persistence\Eloquent\Repositories;

use App\Domain\Categories\Entities\Category;
use App\Domain\Categories\Repositories\CategoryRepositoryInterface;
use App\Infrastructure\Categories\Persistence\Eloquent\Models\CategoryModel;
use Illuminate\Support\Facades\DB;

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
        return DB::table('categories')
            ->select(['id', 'name', 'created_at', 'updated_at'])
            ->orderBy('name', 'asc')
            ->get()
            ->map(fn($row) => new Category(
                id: (string) $row->id,
                name: $row->name,
                createdAt: $row->created_at ? (string) $row->created_at : null,
                updatedAt: $row->updated_at ? (string) $row->updated_at : null
            ))
            ->toArray();
    }

    public function findById(string $id): ?Category
    {
        $row = DB::table('categories')
            ->select(['id', 'name', 'created_at', 'updated_at'])
            ->where('id', $id)
            ->first();

        if (!$row) {
            return null;
        }

        return new Category(
            id: (string) $row->id,
            name: $row->name,
            createdAt: $row->created_at ? (string) $row->created_at : null,
            updatedAt: $row->updated_at ? (string) $row->updated_at : null
        );
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
        $query = DB::table('categories')->where('name', $name);
        if ($exceptId !== null) {
            $query->where('id', '!=', $exceptId);
        }
        return $query->exists();
    }

    public function hasAssociatedProducts(string $categoryId): bool
    {
        return DB::table('products')->where('category_id', $categoryId)->exists();
    }

    public function count(): int
    {
        return DB::table('categories')->count();
    }

    public function paginate(int $perPage = 10, int $page = 1): array
    {
        $total = DB::table('categories')->count();
        $offset = max(0, ($page - 1) * $perPage);

        $items = DB::table('categories')
            ->select(['id', 'name', 'created_at', 'updated_at'])
            ->orderBy('name', 'asc')
            ->offset($offset)
            ->limit($perPage)
            ->get()
            ->map(fn($row) => new Category(
                id: (string) $row->id,
                name: $row->name,
                createdAt: $row->created_at ? (string) $row->created_at : null,
                updatedAt: $row->updated_at ? (string) $row->updated_at : null
            ))
            ->toArray();

        $lastPage = $total > 0 ? (int) ceil($total / $perPage) : 1;

        return [
            'items' => $items,
            'meta' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => $lastPage,
            ]
        ];
    }
}
