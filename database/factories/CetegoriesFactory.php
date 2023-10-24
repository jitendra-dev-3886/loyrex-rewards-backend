<?php

namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Category\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'parent_id' => rand(0, 4),
        'step' => $faker->numberBetween($min = 1, $max = 5),
        'created_at' => now(),
        'updated_at' => now(),
        'created_by' => '1',
        'updated_by' => '1',
    ];
});
