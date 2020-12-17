<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_costs', function (Blueprint $table) {
            $table->id();
            $table->integer('delivery_id')->unsigned()->nullable();
            $table->integer('waybill_id')->unsigned()->nullable();
            $table->integer('branch_id')->unsting();
            $table->integer('company_expense_id')->unsigned();
            $table->integer('employee_id')->unsigned()->nullable();
            $table->integer('car_id')->unsigned()->nullable();
            $table->decimal('amount', 10, 2)->default('0.00');
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
        Schema::dropIfExists('delivery_costs');
    }
}
