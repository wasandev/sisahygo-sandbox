<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithholdingtaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withholdingtaxes', function (Blueprint $table) {
            $table->id();
            $table->date('pay_date');
            $table->char('payertype');
            $table->integer('vendor_id')->unsigned();
            $table->integer('incometype_id')->unsigned();
            $table->decimal('pay_amount', 10, 2);
            $table->decimal('tax_amount', 10, 2);
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
        Schema::dropIfExists('withholdingtaxs');
    }
}
