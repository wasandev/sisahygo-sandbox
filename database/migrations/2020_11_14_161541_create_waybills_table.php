<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaybillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('waybills', function (Blueprint $table) {
            $table->id();
            $table->char('waybill_no', 15)->unique();
            $table->date('waybill_date');
            $table->enum('waybill_status', ['loading', 'comfirmed', 'transporting', 'destinated', 'completed', 'cancel', 'problem'])->default('loading');
            $table->enum('waybill_type', ['general', 'charter', 'express'])->default('General');
            $table->integer('routeto_branch_id')->nullable();
            $table->integer('charter_route_id')->nullable();
            $table->integer('car_id')->unsigned();
            $table->integer('driver_id')->unsigned();
            $table->integer('branchcar_id')->nullable();
            $table->decimal('waybill_amount', 10, 2)->default(0.00);
            $table->decimal('waybill_payable', 10, 2)->default(0.00);
            $table->decimal('waybill_income', 10, 2)->default(0.00);
            $table->decimal('branch_car_rate', 10, 2)->default(0.00);
            $table->decimal('branch_car_income', 10, 2)->default(0.00);
            $table->integer('loader_id')->unsigned();
            $table->dateTime('departure_at')->nullable();
            $table->dateTime('arrival_at')->nullable();
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
        Schema::dropIfExists('waybills');
    }
}
