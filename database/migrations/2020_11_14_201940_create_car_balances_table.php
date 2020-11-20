<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_balances', function (Blueprint $table) {
            $table->id();
            $table->integer('car_id')->unsigned();
            $table->integer('vendor_id');
            $table->char('doctype');
            $table->string('docno');
            $table->string('description');
            $table->decimal('amount', 10, 2);
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
        Schema::dropIfExists('car_balances');
    }
}
