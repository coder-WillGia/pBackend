<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Infrastructure\Categories\Persistence\Eloquent\Models\CategoryModel;
use App\Infrastructure\Products\Persistence\Eloquent\Models\ProductModel;
use Illuminate\Support\Facades\DB;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_categories(): void
    {
        CategoryModel::create(['name' => 'Categoria 1']);
        CategoryModel::create(['name' => 'Categoria 2']);

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Categorías obtenidas correctamente',
            ])
            ->assertJsonCount(2, 'data');
    }

    public function test_can_create_category(): void
    {
        $response = $this->postJson('/api/categories', [
            'name' => 'Nueva Categoria',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Categoría creada correctamente',
                'data' => [
                    'name' => 'Nueva Categoria',
                ],
            ]);

        $this->assertDatabaseHas('categories', ['name' => 'Nueva Categoria']);

        // Check Spatie Activity Log
        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'category',
            'description' => 'Category created',
        ]);
    }

    public function test_create_category_validation_fails_when_name_is_missing(): void
    {
        $response = $this->postJson('/api/categories', []);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Los datos proporcionados no son válidos',
            ])
            ->assertJsonValidationErrors(['name']);
    }

    public function test_create_category_validation_fails_when_name_is_duplicate(): void
    {
        CategoryModel::create(['name' => 'Duplicada']);

        $response = $this->postJson('/api/categories', [
            'name' => 'Duplicada',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_can_show_category_by_id(): void
    {
        $category = CategoryModel::create(['name' => 'Mostrar Categoria']);

        $response = $this->getJson("/api/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Categoría obtenida correctamente',
                'data' => [
                    'id' => $category->id,
                    'name' => 'Mostrar Categoria',
                ],
            ]);
    }

    public function test_show_category_returns_404_when_not_found(): void
    {
        $response = $this->getJson('/api/categories/9999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Recurso no encontrado',
            ]);
    }

    public function test_can_update_category(): void
    {
        $category = CategoryModel::create(['name' => 'Categoria Vieja']);

        $response = $this->putJson("/api/categories/{$category->id}", [
            'name' => 'Categoria Nueva',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Categoría actualizada correctamente',
                'data' => [
                    'id' => $category->id,
                    'name' => 'Categoria Nueva',
                ],
            ]);

        $this->assertDatabaseHas('categories', ['name' => 'Categoria Nueva']);

        // Check Spatie Activity Log
        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'category',
            'description' => 'Category updated',
        ]);
    }

    public function test_can_delete_category(): void
    {
        $category = CategoryModel::create(['name' => 'Para Eliminar']);

        $response = $this->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Categoría eliminada correctamente',
            ]);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);

        // Check Spatie Activity Log
        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'category',
            'description' => 'Category deleted',
        ]);
    }

    public function test_cannot_delete_category_with_associated_products(): void
    {
        $category = CategoryModel::create(['name' => 'Categoria Con Productos']);
        ProductModel::create([
            'name' => 'Producto 1',
            'description' => 'Desc',
            'price' => 10.0,
            'stock' => 5,
            'category_id' => $category->id,
        ]);

        $response = $this->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(400)
            ->assertJson([
                'success' => false,
                'message' => 'No se puede eliminar la categoría porque tiene productos asociados.',
            ]);

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}
