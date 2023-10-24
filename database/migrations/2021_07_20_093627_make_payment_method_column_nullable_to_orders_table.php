<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MakePaymentMethodColumnNullableToOrdersTable extends Migration
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
                COLUMN payment_method  payment_method  ENUM('1')
            COMMENT '1 - Stripe' NULL ;
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
            COLUMN payment_method  payment_method  ENUM('1')
            COMMENT '1 - Stripe' NOT NULL;
");
        });
    }
}
