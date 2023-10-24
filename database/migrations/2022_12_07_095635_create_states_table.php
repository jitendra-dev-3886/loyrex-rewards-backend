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
        Schema::create('states', function (Blueprint $table) {
            $table->increments('id')->index()->comment('AUTO_INCREMENT');
            $table->string('name', 191)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('created_by')->index()->nullable()->comment('Users table ID');
            $table->unsignedInteger('updated_by')->index()->nullable()->comment('Users table ID');
        });

        DB::table('states')->insert(array(
            array('name' => "Andaman and Nicobar Islands", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Andhra Pradesh", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Arunachal Pradesh", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Assam", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Bihar", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Chandigarh", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Chhattisgarh", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Dadra and Nagar Haveli", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Daman and Diu", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Delhi", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Goa", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Gujarat", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Haryana", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Himachal Pradesh", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Jammu and Kashmir", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Jharkhand", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Karnataka", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Kenmore", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Kerala", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Lakshadweep", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Madhya Pradesh", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Maharashtra", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Manipur", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Meghalaya", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Mizoram", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Nagaland", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Narora", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Natwar", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Odisha", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Paschim Medinipur", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Pondicherry", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Punjab", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Rajasthan", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Sikkim", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Tamil Nadu", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Telangana", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Tripura", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Uttar Pradesh", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Uttarakhand", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "Vaishali", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
            array('name' => "West Bengal", 'created_at' => config('constants.calender.date_time'), 'updated_at' => config('constants.calender.date_time')),
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('states');
    }
};
