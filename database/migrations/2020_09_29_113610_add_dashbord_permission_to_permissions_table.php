<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDashbordPermissionToPermissionsTable extends Migration
{
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
            array('id' => 76, 'name' => 'dashboard','label' => 'Dashboard','guard_name' => 'root','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 77, 'name' => 'index-dashboards', 'label' => 'View', 'guard_name' => 'dashboard','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

        ));

        DB::table('permission_role')->insert(array(
            array('permission_id' => '76','role_id' =>'1'),
            array('permission_id' => '77','role_id' =>'1'),

            array('permission_id' => '76','role_id' =>'2'),
            array('permission_id' => '77','role_id' =>'2'),
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
}
