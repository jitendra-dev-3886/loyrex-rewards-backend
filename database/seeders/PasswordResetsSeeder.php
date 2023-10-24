<?php

namespace Database\Seeders;

use App\Models\Auth\PasswordReset;
use Illuminate\Database\Seeder;

class PasswordResetsSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        factory(PasswordReset::class, 10)->create();
    }
}
