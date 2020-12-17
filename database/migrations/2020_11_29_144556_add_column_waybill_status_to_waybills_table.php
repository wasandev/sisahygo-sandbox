<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnWaybillStatusToWaybillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('waybills', function (Blueprint $table) {
            $table->enum('waybill_status', ['loading', 'confirmed', 'in transit', 'arrival', 'delivery', 'completed', 'cancel', 'problem'])->default('loading');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('waybills', function (Blueprint $table) {
            $table->dropColumn('waybill_status');
        });
    }
}
