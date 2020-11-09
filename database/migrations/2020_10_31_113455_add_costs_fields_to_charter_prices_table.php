<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCostsFieldsToCharterPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('charter_prices', function (Blueprint $table) {
            $table->double('fuel_cost', 8, 2)->nullable()->default(0.00);
            $table->double('fuel_amount', 8, 2)->nullable()->default(0.00);
            $table->decimal('timespent', 5, 2)->nullable();
            $table->double('car_charge', 8, 2)->nullable()->default(0.00);
            $table->double('driver_charge', 8, 2)->nullable()->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('charter_prices', function (Blueprint $table) {
            $table->dropColumn('fuel_cost');
            $table->dropColumn('fuel_amount');
            $table->dropColumn('timespent');
            $table->dropColumn('car_charge');
            $table->dropColumn('driver_charge');
        });
    }
}
