<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id')->index()->comment('AUTO_INCREMENT');
            $table->string('name',191)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('created_by')->index()->nullable()->comment('Users table ID');
            $table->unsignedInteger('updated_by')->index()->nullable()->comment('Users table ID');
        });

        DB::table('roles')->insert(array(
            array('name' => 'Master Admin','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
            array('name' => 'Sub Admin','created_at' => config('constants.calender.date_time'),'updated_at' => config('constants.calender.date_time')),
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
