<?php

namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Order\Order;
use App\Models\Order\OrderProducts;
use App\Models\Order\OrderStatus;
use Faker\Generator as Faker;

$factory->define(OrderStatus::class, function (Faker $faker) {
    return [
        'created_at' => now(),
        'updated_at' => now(),
        'created_by' => '1',
        'updated_by' => '1',
    ];
});
