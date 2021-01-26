<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceiptIdToOrderHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_headers', function (Blueprint $table) {
            $table->bigInteger('receipt_id')->unsigned()->nullable();
            $table->boolean('receipt_flag')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_headers', function (Blueprint $table) {
            $table->dropColumn('receipt_id');
            $table->dropColumn('receipt_flag');
        });
    }
}
