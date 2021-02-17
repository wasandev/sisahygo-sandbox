<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChargeflagToRoutetoBranchCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('routeto_branch_costs', function (Blueprint $table) {
            $table->boolean('chargeflag')->default(false);
            $table->decimal('chargerate', 5, 2)->default('0.00')->nullable();
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
            $table->dropColumn('chaegeflag');
            $table->dropColumn('chargerate');
        });
    }
}
