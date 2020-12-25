<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDiscountAmountToBranchBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branch_balances', function (Blueprint $table) {
            $table->decimal('discount_amount', 10, 2)->nullable()->change();
            $table->decimal('tax_amount', 10, 2)->nullable()->change();
            $table->decimal('pay_amount', 10, 2)->nullable()->change();
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
            //
        });
    }
}
