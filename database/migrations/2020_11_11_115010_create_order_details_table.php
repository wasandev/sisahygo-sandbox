<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_header_id')->unsigned();
            $table->boolean('usepricetable')->default(true);
            $table->bigInteger('productservice_price_id')->nullable();
            $table->bigInteger('product_id')->nullable();
            $table->integer('unit_id')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('remark', 200)->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
        });
        Schema::table('order_details', function (Blueprint $table) {

            $table->foreign('order_header_id')
                ->references('id')
                ->on('order_headers')
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
        Schema::dropIfExists('order_details');
    }
}
