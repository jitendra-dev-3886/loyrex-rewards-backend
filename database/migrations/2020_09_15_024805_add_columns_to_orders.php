<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('voucher_id')->index()->nullable()->comment('Vouchers table ID');
            $table->foreign('voucher_id')->references('id')->on('vouchers');
            $table->string('voucher_code',191)->nullable();
            $table->string('voucher_link',255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['voucher_id', 'voucher_code', 'voucher_link']);
        });
    }
}
