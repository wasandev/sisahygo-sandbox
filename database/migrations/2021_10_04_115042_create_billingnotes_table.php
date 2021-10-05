<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingnotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billingnotes', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['new', 'billed', 'cancel'])->default('new');
            $table->date('billingnote_date');
            $table->integer('customer_id')->unsigned();
            $table->char('billing_by')->default('1');
            $table->string('description')->nullable();
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
        Schema::dropIfExists('billingnotes');
    }
}
