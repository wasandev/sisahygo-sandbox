<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShiptocenterFlagToOrderHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_headers', function (Blueprint $table) {

            $table->enum('shipto_center', ['0', '1', '2'])->default('2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_headers', function (Blueprint $table) {
            $table->dropColumn('shipto_center');
        });
    }
}
