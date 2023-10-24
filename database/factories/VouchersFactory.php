<?php

namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Voucher\Voucher;
use Faker\Generator as Faker;

$factory->define(Voucher::class, function (Faker $faker) {
    return [
        'category_id' => $faker->numberBetween($min = 1, $max = 10),
        'name' => $faker->word,
        'description' => $faker->text(100),
        // 'voucher_type' => $faker->numberBetween($min = 0, $max = 1),
        'voucher_type' => '1',
        'no_of_vouchers' => '1',
        'points' => $faker->numberBetween($min = 1000, $max = 1500),
        'user_id' => $faker->numberBetween($min = 3, $max = 8),
        'reference_voucher_no' => $faker->numerify('######'),
        'link' => $faker->url,
        'voucher_code' => $faker->numerify('######'),
        // 'status' => $faker->numberBetween($min = 0, $max = 1),
        'status' => '1',
        'valid_till' => $faker->dateTimeBetween('+1 week', '+1 month'),
        'created_at' => now(),
        'updated_at' => now(),
        'created_by' => '1',
        'updated_by' => '1',
    ];
});
