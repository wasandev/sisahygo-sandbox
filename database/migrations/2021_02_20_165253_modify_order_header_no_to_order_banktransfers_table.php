<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyOrderHeaderNoToOrderBanktransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_banktransfers', function (Blueprint $table) {
            $table->bigInteger('order_header_id')->unsigned()->nullable()->change();
            $table->bigInteger('invoice_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_banktransfers', function (Blueprint $table) {
            $table->dropColumn('invoice_id');
        });
    }
}
