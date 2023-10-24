<?php

namespace Database\Seeders;

use App\Models\Auth\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        factory(Role::class, 10)->create();
    }
}
