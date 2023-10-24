<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCataloguesPermissionInPermissionsTable extends Migration
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
            array('id' => 88, 'name' => 'managecatalogues','label' => 'Manage Catalogue','guard_name' => 'root','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 89, 'name' => 'index-catalogues', 'label' => 'View', 'guard_name' => 'managecatalogues','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 90, 'name' => 'show-catalogues', 'label' => 'Show', 'guard_name' => 'managecatalogues','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 91, 'name' => 'store-catalogues', 'label' => 'Add', 'guard_name' => 'managecatalogues','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 92, 'name' => 'update-catalogues', 'label' => 'Update', 'guard_name' => 'managecatalogues','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 93, 'name' => 'destroy-catalogues', 'label' => 'Delete', 'guard_name' => 'managecatalogues','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 94, 'name' => 'deleteAll-catalogues', 'label' => 'Delete All', 'guard_name' => 'managecatalogues','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 95, 'name' => 'export-catalogues', 'label' => 'Export', 'guard_name' => 'managecatalogues','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
        ));

        DB::table('permission_role')->insert(array(
            array('permission_id' => '88','role_id' =>'1'),
            array('permission_id' => '89','role_id' =>'1'),
            array('permission_id' => '90','role_id' =>'1'),
            array('permission_id' => '91','role_id' =>'1'),
            array('permission_id' => '92','role_id' =>'1'),
            array('permission_id' => '93','role_id' =>'1'),
            array('permission_id' => '94','role_id' =>'1'),
            array('permission_id' => '95','role_id' =>'1'),

            array('permission_id' => '88','role_id' =>'2'),
            array('permission_id' => '89','role_id' =>'2'),
            array('permission_id' => '90','role_id' =>'2'),
            array('permission_id' => '91','role_id' =>'2'),
            array('permission_id' => '92','role_id' =>'2'),
            array('permission_id' => '93','role_id' =>'2'),
            array('permission_id' => '94','role_id' =>'2'),
            array('permission_id' => '95','role_id' =>'2'),
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
