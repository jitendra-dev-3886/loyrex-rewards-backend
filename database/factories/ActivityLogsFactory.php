<?php

namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User\ActivityLog;
use Faker\Generator as Faker;

$factory->define(ActivityLog::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween($min = 3, $max = 7),
        'key' => 'Login',
        'data' => $faker->text(50),
        'created_at' => $faker->dateTimeBetween('+1 week', '+1 month'),
        'updated_at' => $faker->dateTimeBetween('+1 week', '+1 month'),
    ];
});
