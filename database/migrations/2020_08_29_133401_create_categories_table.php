<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id')->index()->unique()->comment('AUTO_INCREMENT');
            $table->unsignedBigInteger('parent_id')->index()->nullable()->comment('Parent ID');
            $table->string('name', 191)->nullable();
            $table->unsignedBigInteger('step')->nullable()->comment('Parent Child Level');
            $table->unsignedBigInteger('created_by')->index()->nullable()->comment('Users table ID');
            $table->unsignedBigInteger('updated_by')->index()->nullable()->comment('Users table ID');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
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
        Schema::dropIfExists('categories');
    }
}
