<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnArrivaledAtToWaybillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('waybills', function (Blueprint $table) {
            $table->dateTime('arrivaled_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('waybills', function (Blueprint $table) {
            $table->dropColumn('arrivaled_at');
        });
    }
}
