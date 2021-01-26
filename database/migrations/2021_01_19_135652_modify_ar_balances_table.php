<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyArBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ar_balances', function (Blueprint $table) {
            if (Schema::hasColumn('ar_balances', 'doctype')) {
                $table->dropColumn('doctype');
            }
            if (Schema::hasColumn('ar_balances', 'docno')) {
                $table->dropColumn('docno');
            }
            if (Schema::hasColumn('ar_balances', 'pay_amount')) {
                $table->dropColumn('pay_amount');
            }
            if (Schema::hasColumn('ar_balances', 'tax_amount')) {
                $table->dropColumn('tax_amount');
            }
            if (Schema::hasColumn('ar_balances', 'discount_amount')) {
                $table->dropColumn('discount_amount');
            }
            if (Schema::hasColumn('ar_balances', 'receipt_no')) {
                $table->dropColumn('receipt_no');
            }
            if (Schema::hasColumn('ar_balances', 'amount')) {
                $table->renameColumn('amount', 'ar_amount');
            }
            $table->bigInteger('order_header_id')->unsigned()->after('customer_id');
            $table->bigInteger('receipt_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('ar_balances', function (Blueprint $table) {
            $table->dropColumn('order_header_id');
            $table->dropColumn('receipt_id');
        });
    }
}
