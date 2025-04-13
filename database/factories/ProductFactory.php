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
  public function definition(): array
  {
    return [
      'name' => $this->faker->word(),
      'description' => $this->faker->sentence(),
      'sku' => $this->faker->unique()->randomNumber(),
      'barcode' => $this->faker->unique()->randomNumber(),
      'price' => $this->faker->randomFloat(2, 1, 100),
      'stock' => $this->faker->numberBetween(1, 100),
      'discounted_price' => $this->faker->randomFloat(2, 1, 100),
      'status' => $this->faker->randomElement(['deleted', 'publish', 'inactive']),
      'category_id' => $this->faker->numberBetween(1, 10), // Assuming you have 20 categories
      'created_at' => now(),
      'updated_at' => now(),
    ];
  }
}
