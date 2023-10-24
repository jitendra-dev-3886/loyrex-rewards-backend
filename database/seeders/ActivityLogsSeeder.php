<?php

namespace Database\Seeders;

use App\Models\User\ActivityLog;
use Illuminate\Database\Seeder;

class ActivityLogsSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        factory(ActivityLog::class, 10)->create();
    }
}
