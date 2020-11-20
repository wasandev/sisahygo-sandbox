<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_balances', function (Blueprint $table) {
            $table->id();
            $table->integer('branch_id')->unsigned();
            $table->bigInteger('waybill_id')->unsigned();
            $table->bigInteger('order_header_id')->unsigned();
            $table->decimal('bal_amount', 10, 2);
            $table->decimal('discount_amount', 10, 2);
            $table->decimal('tax_amount', 10, 2);
            $table->decimal('pay_amount', 10, 2);
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
        Schema::dropIfExists('branch_balances');
    }
}
