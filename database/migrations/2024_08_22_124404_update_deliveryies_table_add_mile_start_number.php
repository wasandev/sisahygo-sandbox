<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deliveries', function (Blueprint $table) {           
            $table->integer('mile_start_number')->unsigned()->nullable();
            $table->integer('mile_end_number')->unsigned()->nullable();
            $table->integer('delivery_mile')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn('mile_start_number');
            $table->dropColumn('mile_end_number');
            $table->dropColumn('delivery_mile');
        });
    }
};
