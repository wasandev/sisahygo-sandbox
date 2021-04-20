<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductIdToCharterPriceQuotation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('charter_price_quotation', function (Blueprint $table) {
            $table->bigInteger('product_id')->nullable();
            $table->integer('unit_id')->nullable();
            $table->string('description')->nullable();
            $table->decimal('product_amount', 10, 2)->nullable();
            $table->decimal('product_weight', 10, 2)->nullable();
            $table->decimal('charter_amount', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('charter_price_quotation', function (Blueprint $table) {
            $table->dropColumn('product_id');
            $table->dropColumn('unit_id');
            $table->dropColumn('description');
            $table->dropColumn('product_amount');
            $table->dropColumn('product_weight');
            $table->dropColumn('charter_amount');
        });
    }
}
