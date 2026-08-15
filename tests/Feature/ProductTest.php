<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Infrastructure\Categories\Persistence\Eloquent\Models\CategoryModel;
use App\Infrastructure\Products\Persistence\Eloquent\Models\ProductModel;
use Illuminate\Support\Facades\DB;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected CategoryModel $category;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a default category to use in tests
        $this->category = CategoryModel::create(['name' => 'Categoria Default']);
    }

    public function test_can_list_products(): void
    {
        ProductModel::create([
            'name' => 'Producto 1',
            'description' => 'Desc 1',
            'price' => 50.0,
            'stock' => 10,
            'category_id' => $this->category->id,
        ]);

        ProductModel::create([
            'name' => 'Producto 2',
            'description' => 'Desc 2',
            'price' => 100.0,
            'stock' => 5,
            'category_id' => $this->category->id,
        ]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Productos obtenidos correctamente',
            ])
            ->assertJsonCount(2, 'data');
    }

    public function test_can_create_product(): void
    {
        $response = $this->postJson('/api/products', [
            'name' => 'Nuevo Producto',
            'description' => 'Descripcion del producto',
            'price' => 19.99,
            'stock' => 150,
            'category_id' => $this->category->id,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Producto creado correctamente',
                'data' => [
                    'name' => 'Nuevo Producto',
                    'price' => 19.99,
                    'stock' => 150,
                    'category_id' => $this->category->id,
                ],
            ]);

        $this->assertDatabaseHas('products', ['name' => 'Nuevo Producto']);

        // Check Spatie Activity Log
        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'product',
            'description' => 'Product created',
        ]);
    }

    public function test_create_product_validation_fails_on_missing_required_fields(): void
    {
        $response = $this->postJson('/api/products', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'price', 'stock', 'category_id']);
    }

    public function test_create_product_fails_when_category_does_not_exist(): void
    {
        $response = $this->postJson('/api/products', [
            'name' => 'Producto Invalido',
            'price' => 10.0,
            'stock' => 5,
            'category_id' => '00000000-0000-0000-0000-000000000000', // Category that doesn't exist
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['category_id']);
    }

    public function test_can_show_product_by_id(): void
    {
        $product = ProductModel::create([
            'name' => 'Detalle Producto',
            'description' => 'Desc',
            'price' => 12.50,
            'stock' => 20,
            'category_id' => $this->category->id,
        ]);

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Producto obtenido correctamente',
                'data' => [
                    'id' => $product->id,
                    'name' => 'Detalle Producto',
                ],
            ]);
    }

    public function test_show_product_returns_404_when_not_found(): void
    {
        $response = $this->getJson('/api/products/9999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Recurso no encontrado',
            ]);
    }

    public function test_can_update_product(): void
    {
        $product = ProductModel::create([
            'name' => 'Producto Viejo',
            'description' => 'Viejo',
            'price' => 10.0,
            'stock' => 1,
            'category_id' => $this->category->id,
        ]);

        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Producto Nuevo',
            'description' => 'Nuevo',
            'price' => 20.0,
            'stock' => 10,
            'category_id' => $this->category->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Producto actualizado correctamente',
                'data' => [
                    'id' => $product->id,
                    'name' => 'Producto Nuevo',
                    'price' => 20.0,
                ],
            ]);

        $this->assertDatabaseHas('products', ['name' => 'Producto Nuevo']);

        // Check Spatie Activity Log
        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'product',
            'description' => 'Product updated',
        ]);
    }

    public function test_can_delete_product(): void
    {
        $product = ProductModel::create([
            'name' => 'Producto Eliminar',
            'price' => 5.0,
            'stock' => 2,
            'category_id' => $this->category->id,
        ]);

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Producto eliminado correctamente',
            ]);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);

        // Check Spatie Activity Log
        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'product',
            'description' => 'Product deleted',
        ]);
    }
}
