<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountAmountToDeliveryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_items', function (Blueprint $table) {
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->decimal('tax_amount', 10, 2)->nullable();
            $table->decimal('pay_amount', 10, 2)->nullable();
            $table->enum('branchpay_by', ['C', 'T', 'Q', 'R'])->default('C')->nullable();
            $table->integer('bankaccount_id')->nullable();
            $table->string('bankreference')->nullable();
            $table->string('chequeno')->nullable();
            $table->string('chequedate')->nullable();
            $table->integer('chequebank_id')->nullable();
            $table->string('description')->nullable();
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
            $table->dropColumn('discount_amount');
            $table->dropColumn('tax_amount');
            $table->dropColumn('pay_amount');
            $table->dropColumn('branchpay_by');
            $table->dropColumn('bankaccount_id');
            $table->dropColumn('bankreference');
            $table->dropColumn('chequeno');
            $table->dropColumn('chequedate');
            $table->dropColumn('chequebank_id');
            $table->dropColumn('description');
        });
    }
}
