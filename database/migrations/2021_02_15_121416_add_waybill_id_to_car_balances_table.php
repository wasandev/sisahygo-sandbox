<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWaybillIdToCarBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_balances', function (Blueprint $table) {
            $table->bigInteger('waybill_id')->unsigned()->nullable();
            $table->bigInteger('carpayment_id')->unsigned()->nullable();
            $table->bigInteger('carreceive_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_balances', function (Blueprint $table) {
            $table->dropColumn('waybill_id');
            $table->dropColumn('carpayment_id');
            $table->dropColumn('carreceive_id');
        });
    }
}
