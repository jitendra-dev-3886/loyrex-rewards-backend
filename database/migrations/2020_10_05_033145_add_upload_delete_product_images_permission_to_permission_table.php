<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUploadDeleteProductImagesPermissionToPermissionTable extends Migration
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
            array('id' => 78, 'name' => 'uploadImage-products','label' => 'Upload Image','guard_name' => 'catalogue','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 79, 'name' => 'deleteImage-products', 'label' => 'Delete Image', 'guard_name' => 'catalogue','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
        ));

        DB::table('permission_role')->insert(array(
            array('permission_id' => '78','role_id' =>'1'),
            array('permission_id' => '79','role_id' =>'1'),

            array('permission_id' => '78','role_id' =>'2'),
            array('permission_id' => '79','role_id' =>'2'),
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
