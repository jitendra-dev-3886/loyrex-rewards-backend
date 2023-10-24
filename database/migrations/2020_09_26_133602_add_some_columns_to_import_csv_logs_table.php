<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColumnsToImportCsvLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('import_csv_logs', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable()->index()->comment('Users table id')->after('model_name');
            $table->foreign('user_id')->references('id')->on('users');
            $table->enum('status',['0', '1'])->nullable()->index()->comment('0 - Fail, 1 - Success')->default('0')->after('user_id');
            $table->unsignedInteger('no_of_rows')->nullable()->comment('No of csv rows')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('import_csv_logs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['user_id']);
            $table->dropColumn(['user_id']);
            $table->dropColumn(['status','no_of_rows']);
        });
    }
}
