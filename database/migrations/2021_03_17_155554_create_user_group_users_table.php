<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserGroupUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_group_users', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->index()->nullable()->comment('Users table id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('userGroup_id')->index()->nullable()->comment('UserGroup table id');
            $table->foreign('userGroup_id')->references('id')->on('user_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_group_users');
    }
}
