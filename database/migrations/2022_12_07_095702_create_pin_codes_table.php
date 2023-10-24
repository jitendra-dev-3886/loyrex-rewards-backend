<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pin_codes', function (Blueprint $table) {
            $table->increments('id')->index()->comment('AUTO_INCREMENT');
            $table->string('pincode', 10)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('created_by')->index()->nullable()->comment('Users table ID');
            $table->unsignedInteger('updated_by')->index()->nullable()->comment('Users table ID');
        });

        DB::table('pin_codes')->insert(array(
            array('pincode' => "395007", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('pincode' => "394550", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('pincode' => "395001", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('pincode' => "394660", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('pincode' => "394335", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('pincode' => "394210", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pin_codes');
    }
};
