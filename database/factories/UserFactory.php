<?php

namespace Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

// Admin users
$factory->define(User::class, function (Faker $faker) {
    return [
        'role_id' => '2',
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'job_title' => 'Admin',
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$gCb4kNHFsGHu.hgvMo5.W.sI/my48gC9OTVSbwTT7aOnY/kpidUHK', // 123456
        'contact_number' => $faker->numerify('##########'),
        'user_type' => '0',
        'verification_otp' => $faker->numerify('######'),
        'remember_token' => $faker->numerify('############'),
        'status' => '1',
        'created_at' => now(),
        'updated_at' => now(),
        'created_by' => '1',
        'updated_by' => '1',
    ];
});
