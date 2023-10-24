<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use phpDocumentor\Reflection\Types\Null_;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->index()->comment('AUTO_INCREMENT');
            $table->unsignedInteger('role_id')->index()->nullable()->comment('Roles table ID - Only for admin user');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->string('first_name',191)->nullable();
            $table->string('last_name',191)->nullable();
            $table->string('job_title',191)->nullable()->comment('Only for Front-end user');
            $table->string('email',191)->nullable();
            $table->string('password',255)->nullable();
            $table->string('contact_number',191)->nullable();
            $table->decimal('reward_points', 10,2)->nullable()->comment('Point added only for Front-end user'); //Substitute 10,2 for your desired precision
            $table->enum('user_type', ['0', '1'])->index()->nullable()->comment('0 - Admin, 1 - User');
            $table->integer('verification_otp')->length(6)->nullable();
            $table->string('remember_token',255)->nullable()->comment('Token for verification link - Only for Front-end user');
            $table->enum('status', ['0', '1'])->index()->comment('0 - Inactive, 1 - Active');
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('created_by')->index()->nullable()->comment('Users table ID');
            $table->unsignedInteger('updated_by')->index()->nullable()->comment('Users table ID');
        });

        DB::table('users')->insert(array(
            array(
                'role_id'=>'1',
                'first_name' => 'Master',
                'last_name' => 'Admin',
                'job_title' => 'Master Admin',
                'email'=>'admin@gmail.com',
                'password'=>'$2y$10$gCb4kNHFsGHu.hgvMo5.W.sI/my48gC9OTVSbwTT7aOnY/kpidUHK', // 123456
                'contact_number'=>'9999999990',
                'user_type'=>'0',// Master Admin
                'status'=>'1',
                'created_at' => config('constants.calender.date_time'),
                'updated_at' => config('constants.calender.date_time'),
                'created_by'=>'1',
                'updated_by'=>'1'
            ),

            array(
                'role_id'=>'2',
                'first_name' => 'Sub',
                'last_name' => 'Admin',
                'job_title' => 'Sub Admin',
                'email'=>'test@gmail.com',
                'password'=>'$2y$10$gCb4kNHFsGHu.hgvMo5.W.sI/my48gC9OTVSbwTT7aOnY/kpidUHK', // 123456
                'contact_number'=>'9999999991',
                'user_type'=>'0',// Sub Admin
                'status'=>'1',
                'created_at' => config('constants.calender.date_time'),
                'updated_at' => config('constants.calender.date_time'),
                'created_by'=>'1',
                'updated_by'=>'1'
            ),
        ));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
