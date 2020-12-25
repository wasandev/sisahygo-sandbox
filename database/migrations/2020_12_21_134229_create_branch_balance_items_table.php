<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchBalanceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_balance_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('branch_balance_id')->unsigned();
            $table->bigInteger('order_header_id')->unsigned();
            $table->boolean('payment_status')->default('0');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
        });
        Schema::table('branch_balance_items', function (Blueprint $table) {
            $table->foreign('branch_balance_id')
                ->references('id')
                ->on('branch_balances')
                ->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_balance_items');
    }
}
