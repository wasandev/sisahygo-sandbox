<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankaccoutToVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->string('bankaccountno', 20)->nullable();
            $table->integer('bank_id')->nullable();
            $table->string('bankaccountname')->nullable();
            $table->string('bankbranch')->nullable();
            $table->enum('account_type', ['saving', 'current', 'fixed'])->default('saving')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn('bankaccountno');
            $table->dropColumn('bank_id');
            $table->dropColumn('bankaccountname');
            $table->dropColumn('bankbranch');
            $table->dropColumn('account_type');
        });
    }
}
