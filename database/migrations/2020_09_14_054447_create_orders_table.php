<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id')->index()->unique()->comment('AUTO_INCREMENT');
            $table->unsignedInteger('user_id')->index()->nullable()->comment('Users table ID');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('quantity')->nullable();
            $table->unsignedInteger('total_points')->nullable();
            $table->string('first_name',191)->nullable();
            $table->string('last_name',191)->nullable();
            $table->string('email',191)->nullable();
            $table->string('contact_number',191)->nullable();
            $table->text('address')->nullable();
            $table->string('state',191)->nullable();
            $table->string('city',191)->nullable();
            $table->enum('order_status', ['0', '1', '2', '3', '4'])->index()->comment('0 - Pending, 1 - Inprocess, 2 - Shipped, 3 - Delivered, 4 - Cancel');
            $table->text('order_status_remark')->nullable()->comment('Remarks from admin');
            $table->text('user_remark')->nullable()->comment('Remarks from user');
            $table->unsignedInteger('redeemed_points')->nullable();
            $table->unsignedInteger('payment_amount')->nullable();
            $table->string('courier_name',191)->nullable();
            $table->string('tracking_id',191)->nullable();
            $table->string('courier_link',255)->nullable();
            $table->enum('payment_method', ['1'])->index()->comment('1 - Stripe');
            $table->enum('payment_mode', ['0', '1'])->index()->comment('0 - Debit Card, 1 - Credit Card');
            $table->string('payment_transaction_id',191)->nullable();
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
        Schema::dropIfExists('orders');
    }
}
