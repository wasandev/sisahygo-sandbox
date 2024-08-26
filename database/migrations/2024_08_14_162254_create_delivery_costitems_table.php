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
        Schema::create('delivery_costitems', function (Blueprint $table) {
            $table->id();
            $table->integer('delivery_id')->unsigned()->nullable();            
            $table->integer('company_expense_id')->unsigned();
            $table->boolean('personal_costs')->default(false);
            $table->integer('employee_id')->unsigned()->nullable();
            $table->string('description')->nullable();            
            $table->decimal('amount', 10, 2)->default('0.00');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_costitems');
    }
};
