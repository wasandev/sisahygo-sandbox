<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDocnoToArBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ar_balances', function (Blueprint $table) {
            $table->char('doctype', 1)->nullable();
            $table->string('docno')->nullable();
            $table->date('docdate')->nullable();
            $table->bigInteger('order_header_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ar_balances', function (Blueprint $table) {
            $table->dropColumn('doctype');
            $table->dropColumn('docno');
            $table->dropColumn('docdate');
        });
    }
}
