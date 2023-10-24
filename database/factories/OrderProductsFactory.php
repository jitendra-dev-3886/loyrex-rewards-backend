<?php

namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Order\Order;
use App\Models\Order\OrderProducts;
use Faker\Generator as Faker;

$factory->define(OrderProducts::class, function (Faker $faker) {
    return [
        'product_id' => $faker->numberBetween($min = 1, $max = 10),
        'product_name' => $faker->text(20),
        'category_name' => $faker->text(15),
        'product_points' => $faker->numberBetween($min = 1000, $max = 1500),
        'quantity' => $faker->numberBetween($min = 1, $max = 5),
        'points' => $faker->numberBetween($min = 2000, $max = 2500),
        'total_points' => $faker->numberBetween($min = 4000, $max = 5500),
        'created_at' => now(),
        'updated_at' => now(),
        'created_by' => '1',
        'updated_by' => '1',
    ];
});
