<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->increments('id')->index()->comment('AUTO_INCREMENT');
            $table->unsignedInteger('category_id')->index()->nullable()->comment('Categories table ID');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->string('name',191)->nullable();
            $table->text('description')->nullable();
            $table->enum('voucher_type', ['0', '1'])->index()->nullable()->comment('0 - Product, 1 - Points');
            $table->unsignedInteger('no_of_vouchers')->nullable()->comment('Number of vouchers');
            $table->unsignedInteger('points')->nullable()->comment('If voucher_type is points');
            $table->unsignedInteger('user_id')->nullable()->comment('Users table ID');
            $table->enum('status', ['0', '1'])->index()->nullable()->comment('0 - Deactivate, 1 - Activate');
            $table->date('valid_till')->nullable()->comment('yyyy-mm-dd - Empty if No Validity Voucher');
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
        Schema::dropIfExists('vouchers');
    }
}
