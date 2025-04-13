<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image' => function () {
                $index = rand(1, 11); // Adjust based on the number of images you have
                return "public/images/products/product{$index}.jpg";
            },
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
