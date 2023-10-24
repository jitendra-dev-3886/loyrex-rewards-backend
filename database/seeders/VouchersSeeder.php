<?php

namespace Database\Seeders;

use App\Models\Voucher\Voucher;
use Illuminate\Database\Seeder;

class VouchersSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        factory(Voucher::class, 10)->create();
    }
}
