<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_items', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->bigInteger('quotation_id')->unsigned();
            $table->bigInteger('from_address_id')->unsigned();
            $table->bigInteger('to_address_id')->unsigned();
            $table->integer('cartype_id')->unsigned();
            $table->integer('carstyle_id')->unsigned();
            $table->bigInteger('product_id')->unsigned();
            $table->decimal('number', 8, 2)->default(0.00);
            $table->integer('unit_id')->unsigned();
            $table->decimal('total_weight', 8, 2)->nullable()->default(0.00);
            $table->decimal('amount', 8, 2)->default(0.00);
            $table->dateTime('pickup_date')->nullable();
            $table->dateTime('delivery_date')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
        });
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->unique([
                'quotation_id',
                'from_address_id',
                'to_address_id',
                'product_id',
                'unit_id'
            ], 'PrimaryQuotationItems');
            $table->foreign('quotation_id')
                ->references('id')
                ->on('quotations')
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
        Schema::dropIfExists('quotation_items');
    }
}
