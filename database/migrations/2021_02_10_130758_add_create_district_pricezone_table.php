<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreateDistrictPricezoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('district_pricezone', function (Blueprint $table) {
            $table->bigInteger('pricezone_id')->unsigned();
            $table->integer('district_id')->unsigned();
        });
        Schema::table('district_pricezone', function (Blueprint $table) {
            $table->unique([
                'pricezone_id',
                'district_id',
            ], 'PrimaryDistrictPricezone');
            $table->foreign('pricezone_id')
                ->references('id')
                ->on('pricezones')
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
        Schema::dropIfExists('district_pricezone');
    }
}
