<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarpaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carpayments', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(true);
            $table->integer('branch_id')->unsigned();
            $table->enum('type', ['T', 'B'])->default('T');
            $table->char('payment_no', 15)->unique();
            $table->integer('car_id')->unsigned();
            $table->integer('vendor_id')->unsigned();
            $table->date('payment_date');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_by', ['H', 'T', 'Q', 'A'])->default('H');
            $table->integer('bankaccount_id')->nullable();
            $table->string('tobankaccount', 20)->nullable();
            $table->integer('tobank_id')->nullable();
            $table->string('tobankaccountname')->nullable();
            $table->string('chequeno')->nullable();
            $table->string('chequedate')->nullable();
            $table->integer('chequebank_id')->nullable();
            $table->boolean('tax_flag')->default(true);
            $table->decimal('tax_amount', 10, 2)->default(0)->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('carpayments');
    }
}
