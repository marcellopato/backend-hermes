<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        // Usar factory se existir
        if (class_exists('Database\\Factories\\PostFactory')) {
            Post::factory()->count(20)->create();
        } else {
            // Criação manual de posts
            for ($i = 1; $i <= 20; $i++) {
                Post::create([
                    'title' => "Post $i",
                    'content' => "Conteúdo do post $i.",
                    'status' => 'published',
                    'user_id' => 1,
                    'image' => null,
                ]);
            }
        }
    }
} 