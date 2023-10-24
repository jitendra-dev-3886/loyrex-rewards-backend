<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id')->index()->comment('AUTO_INCREMENT');
            $table->string('name',191)->nullable();
            $table->unsignedInteger('category_id')->index()->nullable()->comment('Categories table ID');
            $table->unsignedInteger('sub_category_id')->index()->nullable()->comment('Categories table ID');
            $table->unsignedInteger('brand_id')->index()->nullable()->comment('Brands table ID');
            $table->string('featured_image',500)->nullable();
            $table->text('description')->nullable();
            $table->decimal('point', 10,2)->nullable(); //Substitute 10,2 for your desired precision
            $table->enum('available_status', ['0', '1'])->index()->comment('0 - Not-available, 1 - Available');
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
        Schema::dropIfExists('products');
    }
}
