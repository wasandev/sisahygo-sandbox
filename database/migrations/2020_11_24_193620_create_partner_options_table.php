<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnerOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_options', function (Blueprint $table) {
            $table->id();
            $table->double('month_income', 10, 2)->nullable();
            $table->double('income_ratio', 10, 2)->nullable();
            $table->string('remark')->nullable();
            $table->bigInteger('user_id')->unsigned();
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
        Schema::dropIfExists('partner_options');
    }
}
