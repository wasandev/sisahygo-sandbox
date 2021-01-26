<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressIdToOrderHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_headers', function (Blueprint $table) {
            $table->boolean('use_address')->default(false);
            $table->bigInteger('address_id')->unsigned()->nullable();
            $table->boolean('use_to_address')->default(false);
            $table->bigInteger('to_address_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_header', function (Blueprint $table) {
            $table->dropColumn('use_address');
            $table->dropColumn('address_id');
            $table->dropColumn('use_to_address');
            $table->dropColumn('to_address_id');
        });
    }
}
