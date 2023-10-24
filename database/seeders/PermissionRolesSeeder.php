<?php

namespace Database\Seeders;

use App\Models\Auth\Permission_role;
use Illuminate\Database\Seeder;

class PermissionRolesSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        factory(Permission_role::class, 10)->create();
    }
}
