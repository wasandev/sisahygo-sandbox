<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceChargesOrderHeaderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_charges_order_header', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->unsignedBigInteger('service_charge_id');
            $table->unsignedBigInteger('order_header_id');
            $table->decimal('service_amount', 10, 2)->default(0.00);
            $table->string('description')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->unsignedbigInteger('receipt_id')->nullable();
            $table->timestamps();
            $table->foreign('order_header_id')->references('id')->on('order_headers')->cascadeOnDelete();
            $table->foreign('service_charge_id')->references('id')->on('service_charges')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_charges_order_header');
    }
}
