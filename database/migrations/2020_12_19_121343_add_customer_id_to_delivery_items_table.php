<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerIdToDeliveryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_items', function (Blueprint $table) {
           $table->dropColumn('order_header_id');
            $table->integer('customer_id')->unsigned()->nullable();
            $table->decimal('payment_amount',10,2)->nullable();
            $table->integer('receipt_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_items', function (Blueprint $table) {
            $table->integer('order_header_id')->unsigned()->nullable();
            $table->dropColumn('customer_id');
            $table->dropColumn('receipt_id');
            $table->dropColumn('payment_amount');
        });
    }
}
