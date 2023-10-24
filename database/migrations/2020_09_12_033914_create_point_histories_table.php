<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point_histories', function (Blueprint $table) {
            $table->increments('id')->index()->unique()->comment('AUTO_INCREMENT');
            $table->unsignedInteger('user_id')->index()->nullable()->comment('Users table ID');
            $table->foreign('user_id')->references('id')->on('users');
            $table->enum('action_type', ['0', '1'])->index()->comment('0 - Debit, 1 - Credit');
            $table->unsignedInteger('points')->nullable()->comment('Point added only for Front-end user');
            $table->text('description')->nullable();
            $table->unsignedInteger('order_id')->index()->nullable()->default('0')->comment('Orders table ID');
            // $table->foreign('order_id')->references('id')->on('orders');
            $table->unsignedInteger('created_by')->index()->nullable()->comment('Users table ID');
            $table->unsignedInteger('updated_by')->index()->nullable()->comment('Users table ID');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('point_histories');
    }
}
