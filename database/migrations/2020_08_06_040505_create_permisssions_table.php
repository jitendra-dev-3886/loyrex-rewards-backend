<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermisssionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id')->index()->comment('AUTO_INCREMENT');
            $table->string('name',191)->nullable();
            $table->string('label',191)->nullable();
            $table->string('guard_name',191)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('created_by')->index()->nullable()->comment('Users table ID');
            $table->unsignedInteger('updated_by')->index()->nullable()->comment('Users table ID');
        });

        DB::table('permissions')->insert([
            array('id' => 1, 'name' => 'users', 'label' => 'User', 'guard_name' => 'root','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 2, 'name' => 'orders', 'label' => 'Order', 'guard_name' => 'root','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 3, 'name' => 'catalogue', 'label' => 'Catalogue', 'guard_name' => 'root','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 4, 'name' => 'vouchers', 'label' => 'Voucher', 'guard_name' => 'root','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 5, 'name' => 'brands', 'label' => 'Brand', 'guard_name' => 'root','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 6, 'name' => 'categories', 'label' => 'Category', 'guard_name' => 'root','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 7, 'name' => 'admin-users', 'label' => 'Admin', 'guard_name' => 'root','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 8, 'name' => 'roles', 'label' => 'Role', 'guard_name' => 'root','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 9, 'name' => 'permissions', 'label' => 'Permission', 'guard_name' => 'root','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 10, 'name' => 'permission-role-mappings','label' => 'Permission Role Mapping','guard_name' => 'root','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

            array('id' => 11, 'name' => 'index-users', 'label' => 'View', 'guard_name' => 'users','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 12, 'name' => 'show-users', 'label' => 'Show', 'guard_name' => 'users','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 13, 'name' => 'store-users', 'label' => 'Add', 'guard_name' => 'users','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 14, 'name' => 'update-users', 'label' => 'Update', 'guard_name' => 'users','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 15, 'name' => 'destroy-users', 'label' => 'Delete', 'guard_name' => 'users','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 16, 'name' => 'deleteAll-users', 'label' => 'Delete All', 'guard_name' => 'users','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 17, 'name' => 'importBulk-users', 'label' => 'Import', 'guard_name' => 'users','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 18, 'name' => 'export-users', 'label' => 'Export', 'guard_name' => 'users','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

            array('id' => 19, 'name' => 'index-orders', 'label' => 'View',  'guard_name' => 'orders','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 20, 'name' => 'show-orders', 'label' => 'Show',  'guard_name' => 'orders','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 21, 'name' => 'importBulk-orders', 'label' => 'Import',  'guard_name' => 'orders','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 22, 'name' => 'export-orders', 'label' => 'Export',  'guard_name' => 'orders','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

            array('id' => 23, 'name' => 'index-products', 'label' => 'View', 'guard_name' => 'catalogue','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 24, 'name' => 'show-products', 'label' => 'Show', 'guard_name' => 'catalogue','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 25, 'name' => 'store-products', 'label' => 'Add', 'guard_name' => 'catalogue','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 26, 'name' => 'update-products', 'label' => 'Update', 'guard_name' => 'catalogue','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 27, 'name' => 'destroy-products', 'label' => 'Delete', 'guard_name' => 'catalogue','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 28, 'name' => 'deleteAll-products', 'label' => 'Delete All', 'guard_name' => 'catalogue','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 29, 'name' => 'deleteproductimages-products', 'label' => 'Delete Images', 'guard_name' => 'catalogue','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 30, 'name' => 'importBulk-products', 'label' => 'Import', 'guard_name' => 'catalogue','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 31, 'name' => 'export-products', 'label' => 'Export', 'guard_name' => 'catalogue','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

            array('id' => 32, 'name' => 'index-vouchers', 'label' => 'View', 'guard_name' => 'vouchers','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 33, 'name' => 'show-vouchers', 'label' => 'Show', 'guard_name' => 'vouchers','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 34, 'name' => 'store-vouchers', 'label' => 'Add', 'guard_name' => 'vouchers','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 35, 'name' => 'update-vouchers', 'label' => 'Update', 'guard_name' => 'vouchers','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 36, 'name' => 'importBulk-vouchers', 'label' => 'Import', 'guard_name' => 'vouchers','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 37, 'name' => 'export-vouchers', 'label' => 'Export', 'guard_name' => 'vouchers','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

            array('id' => 38, 'name' => 'index-brands', 'label' => 'View','guard_name' => 'brands','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 39, 'name' => 'show-brands', 'label' => 'Show','guard_name' => 'brands','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 40, 'name' => 'store-brands', 'label' => 'Add','guard_name' => 'brands','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 41, 'name' => 'update-brands', 'label' => 'Update','guard_name' => 'brands','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 42, 'name' => 'destroy-brands', 'label' => 'Delete','guard_name' => 'brands','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 43, 'name' => 'deleteAll-brands', 'label' => 'Delete All','guard_name' => 'brands','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 44, 'name' => 'export-brands', 'label' => 'Export','guard_name' => 'brands','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

            array('id' => 45, 'name' => 'index-categories', 'label' => 'View', 'guard_name' => 'categories','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 46, 'name' => 'show-categories', 'label' => 'Show', 'guard_name' => 'categories','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 47, 'name' => 'store-categories', 'label' => 'Add', 'guard_name' => 'categories','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 48, 'name' => 'update-categories', 'label' => 'Update', 'guard_name' => 'categories','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 49, 'name' => 'destroy-categories', 'label' => 'Delete', 'guard_name' => 'categories','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 50, 'name' => 'deleteAll-categories', 'label' => 'Delete All', 'guard_name' => 'categories','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 51, 'name' => 'export-categories', 'label' => 'Export', 'guard_name' => 'categories','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

            array('id' => 52, 'name' => 'index-users', 'label' => 'View', 'guard_name' => 'admin-users','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 53, 'name' => 'show-users', 'label' => 'Show', 'guard_name' => 'admin-users','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 54, 'name' => 'register-users', 'label' => 'Add', 'guard_name' => 'admin-users','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 55, 'name' => 'update-users', 'label' => 'Update', 'guard_name' => 'admin-users','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 56, 'name' => 'destroy-users', 'label' => 'Delete', 'guard_name' => 'admin-users','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 57, 'name' => 'deleteAll-users', 'label' => 'Delete All', 'guard_name' => 'admin-users','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 58, 'name' => 'importBulk-users', 'label' => 'Import', 'guard_name' => 'admin-users','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 59, 'name' => 'export-users', 'label' => 'Export', 'guard_name' => 'admin-users','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

            array('id' => 60, 'name' => 'index-roles', 'label' => 'View', 'guard_name' => 'roles','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 61, 'name' => 'show-roles', 'label' => 'Show', 'guard_name' => 'roles','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 62, 'name' => 'store-roles', 'label' => 'Add', 'guard_name' => 'roles','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 63, 'name' => 'update-roles', 'label' => 'Update', 'guard_name' => 'roles','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 64, 'name' => 'destroy-roles', 'label' => 'Delete', 'guard_name' => 'roles','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 65, 'name' => 'deleteAll-roles', 'label' => 'Delete All', 'guard_name' => 'roles','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 66, 'name' => 'export-roles', 'label' => 'Export', 'guard_name' => 'roles','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

            array('id' => 67, 'name' => 'index-permissions', 'label' => 'View', 'guard_name' => 'permissions','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 68, 'name' => 'show-permissions', 'label' => 'Show', 'guard_name' => 'permissions','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 69, 'name' => 'store-permissions', 'label' => 'Add', 'guard_name' => 'permissions','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 70, 'name' => 'update-permissions', 'label' => 'Update', 'guard_name' => 'permissions','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 71, 'name' => 'destroy-permissions', 'label' => 'Delete', 'guard_name' => 'permissions','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 72, 'name' => 'deleteAll-permissions', 'label' => 'Delete All', 'guard_name' => 'permissions','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 73, 'name' => 'export-permissions', 'label' => 'Export', 'guard_name' => 'permissions','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),

            array('id' => 74, 'name' => 'setUnsetPermissionToRole-permissions','label' => 'Set/Unset Permission','guard_name' => 'permission-role-mappings','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('id' => 75, 'name' => 'getPermissionsByRole-roles','label' => 'Permissions By Role','guard_name' => 'permission-role-mappings','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
