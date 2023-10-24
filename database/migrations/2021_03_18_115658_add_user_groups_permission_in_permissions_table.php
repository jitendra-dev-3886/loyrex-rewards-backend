<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserGroupsPermissionInPermissionsTable extends Migration
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
            array('id' => 80, 'name' => 'usergroups','label' => 'UserGroup','guard_name' => 'root','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 81, 'name' => 'index-usergroups', 'label' => 'View', 'guard_name' => 'usergroups','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 82, 'name' => 'show-usergroups', 'label' => 'Show', 'guard_name' => 'usergroups','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 83, 'name' => 'store-usergroups', 'label' => 'Add', 'guard_name' => 'usergroups','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 84, 'name' => 'update-usergroups', 'label' => 'Update', 'guard_name' => 'usergroups','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 85, 'name' => 'destroy-usergroups', 'label' => 'Delete', 'guard_name' => 'usergroups','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 86, 'name' => 'deleteAll-usergroups', 'label' => 'Delete All', 'guard_name' => 'usergroups','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 87, 'name' => 'export-usergroups', 'label' => 'Export', 'guard_name' => 'usergroups','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
        ));

        DB::table('permission_role')->insert(array(
            array('permission_id' => '80','role_id' =>'1'),
            array('permission_id' => '81','role_id' =>'1'),
            array('permission_id' => '82','role_id' =>'1'),
            array('permission_id' => '83','role_id' =>'1'),
            array('permission_id' => '84','role_id' =>'1'),
            array('permission_id' => '85','role_id' =>'1'),
            array('permission_id' => '86','role_id' =>'1'),
            array('permission_id' => '87','role_id' =>'1'),

            array('permission_id' => '80','role_id' =>'2'),
            array('permission_id' => '81','role_id' =>'2'),
            array('permission_id' => '82','role_id' =>'2'),
            array('permission_id' => '83','role_id' =>'2'),
            array('permission_id' => '84','role_id' =>'2'),
            array('permission_id' => '85','role_id' =>'2'),
            array('permission_id' => '86','role_id' =>'2'),
            array('permission_id' => '87','role_id' =>'2'),
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
