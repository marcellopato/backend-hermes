<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(6),
            'content' => $this->faker->paragraphs(3, true),
            'image' => $this->faker->imageUrl(640, 480, 'posts', true),
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
            'user_id' => 1, // pode ser ajustado para random ou factory de user
        ];
    }
}
