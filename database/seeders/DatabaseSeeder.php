<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Infrastructure\Categories\Persistence\Eloquent\Models\CategoryModel;
use App\Infrastructure\Products\Persistence\Eloquent\Models\ProductModel;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Categories
        $categoriesData = [
            ['name' => 'Ropa'],
            ['name' => 'Calzado'],
            ['name' => 'Electrónica'],
            ['name' => 'Hogar'],
            ['name' => 'Deportes'],
        ];

        $categories = [];
        foreach ($categoriesData as $cData) {
            $categories[] = CategoryModel::create($cData);
        }

        // 2. Seed Products
        $productsData = [
            [
                'name' => 'Camiseta Overskull',
                'description' => 'Camiseta negra edición limitada de algodón 100%.',
                'price' => 89.90,
                'stock' => 50,
                'category_id' => $categories[0]->id, // Ropa
            ],
            [
                'name' => 'Jeans Slim Fit',
                'description' => 'Pantalones vaqueros entallados color azul clásico.',
                'price' => 149.90,
                'stock' => 30,
                'category_id' => $categories[0]->id, // Ropa
            ],
            [
                'name' => 'Zapatillas Running Pro',
                'description' => 'Calzado deportivo ergonómico con suela de amortiguación.',
                'price' => 299.00,
                'stock' => 20,
                'category_id' => $categories[1]->id, // Calzado
            ],
            [
                'name' => 'Botas de Cuero Elegantes',
                'description' => 'Botas de vestir fabricadas en cuero genuino.',
                'price' => 389.50,
                'stock' => 15,
                'category_id' => $categories[1]->id, // Calzado
            ],
            [
                'name' => 'Smartphone Galaxy X',
                'description' => 'Pantalla de 6.5 pulgadas con 128GB de almacenamiento.',
                'price' => 1299.90,
                'stock' => 10,
                'category_id' => $categories[2]->id, // Electrónica
            ],
            [
                'name' => 'Auriculares Inalámbricos SoundMax',
                'description' => 'Auriculares bluetooth con cancelación activa de ruido.',
                'price' => 199.00,
                'stock' => 40,
                'category_id' => $categories[2]->id, // Electrónica
            ],
            [
                'name' => 'Lámpara de Escritorio LED',
                'description' => 'Lámpara recargable con ajuste táctil de temperatura.',
                'price' => 79.90,
                'stock' => 25,
                'category_id' => $categories[3]->id, // Hogar
            ],
            [
                'name' => 'Sartén de Teflón Antiadherente',
                'description' => 'Sartén de 28cm ideal para inducción y vitrocerámica.',
                'price' => 120.00,
                'stock' => 35,
                'category_id' => $categories[3]->id, // Hogar
            ],
            [
                'name' => 'Balón de Fútbol Regulación FIFA',
                'description' => 'Balón oficial de cuero sintético con costuras reforzadas.',
                'price' => 110.00,
                'stock' => 18,
                'category_id' => $categories[4]->id, // Deportes
            ],
            [
                'name' => 'Mancuernas Ajustables 20kg',
                'description' => 'Kit de mancuernas con discos intercambiables de acero.',
                'price' => 249.90,
                'stock' => 12,
                'category_id' => $categories[4]->id, // Deportes
            ],
        ];

        foreach ($productsData as $pData) {
            ProductModel::create($pData);
        }
    }
}
