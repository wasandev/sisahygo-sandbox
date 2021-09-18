<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderHeaderIdToBranchBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branch_balances', function (Blueprint $table) {
            $table->bigInteger('order_header_id')->unsigned();
            $table->bigInteger('delivery_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branch_balances', function (Blueprint $table) {
            $table->dropColumn('order_header_id');
            $table->dropColumn('delivery_id');
        });
    }
}
