<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'=> fake()->words(5, true),
            'image'=> fake()->imageUrl(),
            'watermark_image'=> fake()->imageUrl(),
            'description'=> fake()->realText(),
        ];
    }
}
