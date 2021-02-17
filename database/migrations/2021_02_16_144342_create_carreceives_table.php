<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarreceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carreceives', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(true);
            $table->integer('branch_id')->unsigned();
            $table->char('receive_no', 15)->unique();
            $table->integer('car_id')->unsigned();
            $table->integer('vendor_id')->unsigned();
            $table->date('receive_date');
            $table->decimal('amount', 10, 2);
            $table->enum('receive_by', ['H', 'T', 'Q', 'A'])->default('H');
            $table->integer('bankaccount_id')->nullable();
            $table->string('frombankaccount', 20)->nullable();
            $table->integer('frombank_id')->nullable();
            $table->string('frombankaccountname')->nullable();
            $table->string('chequeno')->nullable();
            $table->string('chequedate')->nullable();
            $table->integer('chequebank_id')->nullable();
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
        Schema::dropIfExists('carreceives');
    }
}
