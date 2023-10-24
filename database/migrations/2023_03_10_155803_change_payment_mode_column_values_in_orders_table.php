<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
        });

        DB::statement("ALTER TABLE orders CHANGE COLUMN payment_method payment_method ENUM('1') COMMENT '1 - Easebuzz';");

        DB::statement("ALTER TABLE orders CHANGE COLUMN payment_mode payment_mode ENUM('0', '1', '2', '3', '4') COMMENT '0 - Debit Card, 1 - Credit Card, 2 - Net Banking, 3 - UPI, 4 - Other';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
