<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Usar factory se existir
        if (class_exists('Database\\Factories\\ProductFactory')) {
            Product::factory()->count(20)->create();
        } else {
            // Criação manual de produtos
            for ($i = 1; $i <= 20; $i++) {
                Product::create([
                    'name' => "Produto $i",
                    'description' => "Descrição do produto $i.",
                    'price' => rand(100, 10000) / 100,
                    'stock' => rand(0, 50),
                    'image' => null,
                ]);
            }
        }
    }
} 