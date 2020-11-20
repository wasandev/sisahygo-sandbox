<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFulltruckrateToRoutetoBranchCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('routeto_branch_costs', function (Blueprint $table) {
            $table->decimal('fulltruckrate', 10, 2)->nullable();
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
            $table->dropColumn('fulltruckrate');
        });
    }
}
