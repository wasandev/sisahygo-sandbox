<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProblemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_problems', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_header_id')->unsigned();
            $table->char('customer_flag', 1);
            $table->bigInteger('customer_id')->unsigned();
            $table->string('contact_person')->nullable();
            $table->string('contact_phoneno')->nullable();
            $table->char('problem_no', 15)->unique();
            $table->date('problem_date');
            $table->enum('status', ['new', 'checking', 'discuss', 'approved'])->default('new');
            $table->enum('problem_type', ['1', '2', '3', '4', '0']);
            $table->string('problem_detail')->nullable();
            $table->string('problem_claim')->nullable();
            $table->decimal('claim_amount', 10, 2)->nullable();
            $table->string('problem_personclaim')->nullable();
            $table->enum('problem_process', ['1', '2', '3', '4', '5', '6']);
            $table->string('check_detail')->nullable();
            $table->string('discuss_detail')->nullable();
            $table->decimal('approve_amount', 10, 2)->nullable();
            $table->boolean('order_amount_flag')->nullable();
            $table->date('payment_date')->nullable();
            $table->enum('payment_by', ['H', 'T', 'Q'])->default('H');
            $table->string('bankaccountname')->nullable();
            $table->string('bankaccount', 20)->nullable();
            $table->integer('bank_id')->nullable();
            $table->string('chequeno')->nullable();
            $table->string('chequedate')->nullable();
            $table->integer('chequebank_id')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('checker_id')->unsigned()->nullable();
            $table->integer('appprove_id')->unsigned()->nullable();
            $table->integer('employee_id')->unsigned()->nullable();
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
        Schema::dropIfExists('order_problems');
    }
}
