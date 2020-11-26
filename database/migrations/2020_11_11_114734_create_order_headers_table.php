<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_headers', function (Blueprint $table) {
            $table->id();
            $table->char('order_header_no', 15)->nullable();
            $table->date('order_header_date');
            $table->enum('order_status', ['new', 'confirmed', 'loaded', 'transporting', 'completed', 'cancel', 'problem'])->default('new');
            $table->boolean('payment_status')->default(false);
            $table->integer('branch_id')->unsigned();
            $table->integer('branch_rec_id')->unsigned();
            $table->bigInteger('customer_id')->unsigned();
            $table->bigInteger('customer_rec_id')->unsigned();
            $table->enum('paymenttype', ['H', 'T', 'E', 'F', 'L'])->default('H');
            $table->string('remark', 150)->nullable();
            $table->bigInteger('waybill_id')->nullable();
            $table->boolean('trantype')->default(true);
            $table->integer('checker_id')->nullable();
            $table->integer('loader_id')->nullable();
            $table->integer('shipper_id')->nullable();
            $table->decimal('order_amount', 10, 2)->nullable();
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
        Schema::dropIfExists('order_headers');
    }
}
