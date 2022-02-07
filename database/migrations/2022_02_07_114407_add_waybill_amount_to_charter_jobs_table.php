<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWaybillAmountToCharterJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('charter_jobs', function (Blueprint $table) {
            $table->decimal('waybill_amount', 10, 2)->default(0.00);
            $table->decimal('waybill_payable', 10, 2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('charter_jobs', function (Blueprint $table) {
            $table->dropColumn('waybill_amount');
            $table->dropColumn('waybill_payable');
        });
    }
}
