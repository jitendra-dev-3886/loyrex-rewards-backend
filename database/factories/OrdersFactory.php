<?php

namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Order\Order;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween($min = 1, $max = 10),
        'quantity' => $faker->numberBetween($min = 1, $max = 3),
        'total_points' => $faker->numberBetween($min = 1000, $max = 1500),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'contact_number' => rand(6, 9) . $faker->numerify('#########'),
        'address' => $faker->text(20),
        'state' => $faker->state,
        'city' => $faker->city,
        'pin_code' => $faker->numerify('######'),
        'order_status' => rand(1, 3),
        'order_status_remark' => $faker->text(20),
        'user_remark' => $faker->text(20),
        'redeemed_points' => $faker->numberBetween($min = 500, $max = 500),
        'payment_amount' => $faker->numberBetween($min = 500, $max = 500),
        'courier_name' => $faker->text(15),
        'tracking_id' => $faker->numerify('######'),
        'courier_link' => $faker->url,
        'payment_method' => '1',
        'payment_mode' => '1',
        'payment_transaction_id' => $faker->numerify('######'),
        'created_at' => now(),
        'updated_at' => now(),
        'created_by' => '1',
        'updated_by' => '1',
    ];
});
