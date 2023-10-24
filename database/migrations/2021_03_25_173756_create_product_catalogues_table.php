<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCataloguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_catalogue', function (Blueprint $table) {
            $table->unsignedInteger('product_id')->index()->nullable()->comment('Products table id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->unsignedInteger('catalogue_id')->index()->nullable()->comment('Catalogues table id');
            $table->foreign('catalogue_id')->references('id')->on('catalogues');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_catalogue');
    }
}
