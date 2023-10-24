<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToVouchers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->string('reference_voucher_no',191)->nullable()->comment('Auto generate voucher reference code')->after('user_id');
            $table->string('link',255)->nullable()->comment('Create voucher wise unique Link')->after('reference_voucher_no');
            $table->string('voucher_code',191)->nullable()->comment('Create voucher wise unique code')->after('link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(['reference_voucher_no', 'link', 'voucher_code']);
        });
    }
}
