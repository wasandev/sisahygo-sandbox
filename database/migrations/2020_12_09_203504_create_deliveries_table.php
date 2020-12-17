<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('delivery_no', 15)->nullable();
            $table->date('delivery_date');
            $table->integer('waybill_id')->unsigned()->nullable();
            $table->boolean('delivery_type')->default('0');
            $table->integer('branch_id')->unsigned();
            $table->integer('branch_route_id')->unsigned();
            $table->integer('car_id')->unsigned();
            $table->integer('driver_id')->unsigned();
            $table->string('description')->nullable();
            $table->decimal('receipt_amount', 10, 2)->default('0.00');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
}
