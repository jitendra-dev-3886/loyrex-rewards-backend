<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeTwoColumnToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            DB::statement("
        ALTER TABLE orders
            CHANGE
            COLUMN payment_mode payment_mode ENUM('0', '1')
        COMMENT '0 - Debit Card;1 - Credit Card' NULL ;
");
            DB::statement("
        ALTER TABLE orders
            CHANGE
            COLUMN order_status order_status ENUM('0', '1', '2', '3', '4','99')
        COMMENT '0 - Pending; 1 - Inprocess; 2 - Shipped; 3 - Delivered; 4 - Cancel; 99 - Embedded order';
");


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
            DB::statement("
        ALTER TABLE orders
            CHANGE
            COLUMN payment_mode payment_mode ENUM('0', '1')
        COMMENT '0 - Debit Card;1 - Credit Card' NOT NULL;
");
            DB::statement("
        ALTER TABLE orders
            CHANGE
            COLUMN order_status order_status ENUM('0', '1', '2', '3', '4')
        COMMENT '0 - Pending; 1 - Inprocess; 2 - Shipped; 3 - Delivered; 4 - Cancel';
");
        });
    }
}
