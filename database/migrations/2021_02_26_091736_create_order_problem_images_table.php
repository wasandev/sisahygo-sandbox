<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProblemImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_problem_images', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_problem_id')->unsigned();
            $table->string('problemimage')->nullable();
            $table->string('problemfile')->nullable();
            $table->timestamps();
        });
        Schema::table('order_problem_images', function (Blueprint $table) {

            $table->foreign('order_problem_id')
                ->references('id')
                ->on('order_problems')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_problem_images');
    }
}
