<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewOrderStatusColumnToOrderHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_headers', function (Blueprint $table) {
            $table->enum('order_status', ['checking', 'new', 'confirmed', 'loaded', 'in transit', 'arrival', 'branch warehouse', 'delivery', 'completed', 'cancel', 'problem'])->default('new');
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
            //$table->dropColumn('order_status');
        });
    }
}
