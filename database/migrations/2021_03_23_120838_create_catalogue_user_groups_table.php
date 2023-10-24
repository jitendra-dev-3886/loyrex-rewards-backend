<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogueUserGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogue_user_groups', function (Blueprint $table) {
            $table->unsignedInteger('userGroup_id')->index()->nullable()->comment('UserGroup table id');
            $table->foreign('userGroup_id')->references('id')->on('user_groups');
            $table->unsignedInteger('catalogue_id')->index()->nullable()->comment('Catalogue table id');
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
        Schema::dropIfExists('catalogue_user_groups');
    }
}
