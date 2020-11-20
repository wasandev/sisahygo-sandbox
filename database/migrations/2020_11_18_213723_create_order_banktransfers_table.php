<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderBanktransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_banktransfers', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(false);
            $table->bigInteger('order_header_id')->unsigned();
            $table->decimal("transfer_amount", 10, 2);
            $table->integer('bankaccount_id');
            $table->string('reference');
            $table->string('transferslip');
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
        Schema::dropIfExists('order_banktransfers');
    }
}
