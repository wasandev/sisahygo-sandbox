<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpressFeeToDistrictPricezonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('district_pricezone', function (Blueprint $table) {
            $table->decimal('express_fee', 10, 2)->nullable()->default(0.00);
            $table->decimal('faraway_fee', 10, 2)->nullable()->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('district_pricezone', function (Blueprint $table) {
            $table->dropColumn('express_fee');
            $table->dropColumn('faraway_fee');
        });
    }
}
