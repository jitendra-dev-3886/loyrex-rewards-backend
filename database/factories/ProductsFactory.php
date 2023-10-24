<?php

namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->text(20),
        'category_id' => $faker->numberBetween($min = 1, $max = 4),
        'sub_category_id' => $faker->numberBetween($min = 5, $max = 10),
        'brand_id' => $faker->numberBetween($min = 1, $max = 10),
        'featured_image' => '',
        'description' => $faker->paragraph,
        'point' => $faker->numberBetween($min = 1000, $max = 1500),
        'available_status' => '1',
        'created_at' => now(),
        'updated_at' => now(),
        'created_by' => '1',
        'updated_by' => '1',
    ];
});
