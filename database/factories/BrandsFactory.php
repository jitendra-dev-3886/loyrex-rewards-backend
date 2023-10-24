<?php

namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Brand\Brand;
use Faker\Generator as Faker;

$factory->define(Brand::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->text(100),
        'created_at' => now(),
        'updated_at' => now(),
        'created_by' => '1',
        'updated_by' => '1',
    ];
});
