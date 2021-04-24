<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDropshipTransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dropship_trans', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(false);
            $table->integer('branch_id')->unsigned();
            $table->string('dropship_tran_no', 15);
            $table->date('dropship_tran_date');
            $table->integer('employee_id')->unsigned();
            $table->decimal('tran_amount', 10, 2);
            $table->decimal('dropship_income', 10, 2);
            $table->decimal('scash_amount', 10, 2);
            $table->decimal('dcash_amount', 10, 2);
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
        Schema::dropIfExists('dropship_trans');
    }
}
