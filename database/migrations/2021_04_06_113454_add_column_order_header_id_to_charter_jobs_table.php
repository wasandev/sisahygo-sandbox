<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOrderHeaderIdToCharterJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('charter_jobs', function (Blueprint $table) {
            $table->bigInteger('order_header_id')->unsigned()->nullable();
            $table->integer('car_id')->unsigned()->nullable();
            $table->integer('driver_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('charter_jobs', function (Blueprint $table) {
            $table->dropColumn('order_header_id');
            $table->dropColumn('car_id');
            $table->dropColumn('driver_id');
        });
    }
}
