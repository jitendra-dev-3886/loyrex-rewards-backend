<?php

namespace Database\Seeders;

use ActivityLogsSeeder;
use BrandsSeeder;
use CategoriesSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesSeeder::class,
            UserSeeder::class,
            UsersSeeder::class,
            BrandsSeeder::class,
            CategoriesSeeder::class,
            OrdersSeeder::class,
            ProductsSeeder::class,
            VouchersSeeder::class,
            ActivityLogsSeeder::class,
        ]);
    }
}
