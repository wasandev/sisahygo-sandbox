<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchpayByToBranchBalances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branch_balances', function (Blueprint $table) {
            $table->dropColumn('waybill_id');
            $table->dropColumn('order_header_id');
            $table->integer('receipt_id')->unsigned()->nullable();
            $table->integer('customer_id')->unsigned();
            $table->date('branchbal_date')->nullable();
            $table->date('branchpay_date')->nullable();
            $table->boolean('payment_status')->default(false);
            $table->string('remark')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branch_balances', function (Blueprint $table) {
            $table->dropColumn('receipt_id');
            $table->dropColumn('customer_id');
            $table->dropColumn('branchbal_date');
            $table->dropColumn('branchpay_date');
            $table->dropColumn('payment_status');
            $table->dropColumn('remark');
        });
    }
}
