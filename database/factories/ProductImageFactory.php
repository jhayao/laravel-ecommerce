<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => $this->faker->numberBetween(1, 20), // Assuming you have 20 products
            'image_id' => $this->faker->numberBetween(1, 20), // Assuming you have 20 images
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
