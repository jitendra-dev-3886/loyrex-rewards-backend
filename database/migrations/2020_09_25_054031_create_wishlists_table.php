<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWishlistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->increments('id')->index()->unique()->comment('AUTO_INCREMENT');
            $table->unsignedInteger('product_id')->index()->nullable()->comment('Products table ID');
            $table->foreign('product_id')->references('id')->on('products');
            $table->unsignedInteger('user_id')->index()->nullable()->comment('Users table ID');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedInteger('created_by')->index()->nullable()->comment('Users table ID');
            $table->unsignedInteger('updated_by')->index()->nullable()->comment('Users table ID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wishlists');
    }
}
