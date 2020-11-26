<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTimespentColumnFromRoutetoBranchCostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('routeto_branch_costs', function (Blueprint $table) {
            $table->dropColumn('timespent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('routeto_branch_costs', function (Blueprint $table) {
            //
        });
    }
}
