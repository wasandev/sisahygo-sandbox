<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('branch_id')->unsigned()->nullable();
            $table->string('avatar')->default('member.png');
            $table->enum('role', ['admin', 'employee', 'driver', 'customer'])->default('employee');
            $table->string('mobile')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('branch_id');
            $table->dropColumn('avatar');
            $table->dropColumn('role');
            $table->dropColumn('mobile');
        });
    }
}
