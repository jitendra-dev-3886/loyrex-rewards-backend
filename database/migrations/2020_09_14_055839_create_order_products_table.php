<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->increments('id')->index()->unique()->comment('AUTO_INCREMENT');
            $table->unsignedInteger('order_id')->index()->nullable()->comment('Orders table ID');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->unsignedInteger('product_id')->index()->nullable()->comment('Products table ID');
            $table->foreign('product_id')->references('id')->on('products');
            $table->string('product_name',191)->nullable();
            $table->string('category_name',191)->nullable();
            $table->string('featured_image',500)->nullable();
            $table->unsignedInteger('product_points')->nullable();
            $table->unsignedInteger('quantity')->nullable();
            $table->unsignedInteger('points')->nullable();
            $table->unsignedInteger('total_points')->nullable();
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
        Schema::dropIfExists('order_products');
    }
}
