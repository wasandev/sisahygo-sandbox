<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentDateToOrderProblemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_problems', function (Blueprint $table) {
            $table->date('payment_date')->nullable();
            $table->enum('payment_by', ['H', 'T', 'Q'])->default('H');
            $table->string('bankaccountname')->nullable();
            $table->string('bankaccount', 20)->nullable();
            $table->integer('bank_id')->nullable();
            $table->string('chequeno')->nullable();
            $table->string('chequedate')->nullable();
            $table->integer('chequebank_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_problems', function (Blueprint $table) {
            $table->dropColumn('payment_date');
            $table->dropColumn('payment_by');
            $table->dropColumn('bankaccountname');
            $table->dropColumn('bankaccount');
            $table->dropColumn('bank_id');
            $table->dropColumn('chequeno');
            $table->dropColumn('chequedate');
            $table->dropColumn('chequebank_id');
        });
    }
}
