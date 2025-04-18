<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'title' => $this->faker->word(),
      'description' => $this->faker->sentence(),
      'slug' => $this->faker->slug(),
      'parent_id' => null,
      'image' => function () {
        $index = rand(1, 11); // Adjust based on the number of images you have
        return "https://pub-e64b48d6794a40709a9461dc60f7f881.r2.dev/public/images/products/product{$index}.jpg";
      },
      'status' => $this->faker->randomElement(['deleted','publish', 'inactive']),
      'created_at' => now(),
      'updated_at' => now(),
    ];
  }
}
