<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id')->index()->comment('AUTO_INCREMENT');
            $table->string('first_name', 191)->nullable();
            $table->string('last_name', 191)->nullable();
            $table->string('email', 191)->nullable();
            $table->string('subject', 191)->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('permissions')->insert(array(
            array('id' => 96, 'name' => 'managecontacts', 'label' => 'Manage contacts', 'guard_name' => 'root', 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),

            array('id' => 97, 'name' => 'index-contacts', 'label' => 'View', 'guard_name' => 'managecontacts', 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('id' => 98, 'name' => 'export-contacts', 'label' => 'Export', 'guard_name' => 'managecontacts', 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
        ));

        DB::table('permission_role')->insert(array(
            array('permission_id' => '96', 'role_id' => '1'),
            array('permission_id' => '97', 'role_id' => '1'),
            array('permission_id' => '98', 'role_id' => '1'),


            array('permission_id' => '96', 'role_id' => '2'),
            array('permission_id' => '97', 'role_id' => '2'),
            array('permission_id' => '98', 'role_id' => '2'),
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
