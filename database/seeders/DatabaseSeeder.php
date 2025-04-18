<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Image;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
//    User::factory(10)->create();
//
//    User::factory()->create([
//      'name' => 'Test User',
//      'email' => 'test@example.com',
//    ]);
//    $this->call([
//      CustomerSeeder::class,
//    ]);
    Customer::factory()->count(10)->create();
    Category::factory()->count(10)->create();
    Image::factory()->count(11)->create();
    Product::factory(100)->create()->each(function ($product) {
        $product->images()->attach(Image::all()->random(3)->pluck('id')->toArray());
    });


  }
}
