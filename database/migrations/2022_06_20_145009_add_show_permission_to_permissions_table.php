<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
            //
        });

        DB::table('permissions')->insert(array(
            array('id' => 99, 'name' => 'show-contacts', 'label' => 'Show', 'guard_name' => 'managecontacts', 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
        ));

        DB::table('permission_role')->insert(array(
            array('permission_id' => '99', 'role_id' => '1'),
            array('permission_id' => '99', 'role_id' => '2'),
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            //
        });
    }
};
