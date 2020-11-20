<?php

use Doctrine\DBAL\Types\IntegerType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankaccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bankaccounts', function (Blueprint $table) {
            $table->id();
            $table->integer('bank_id')->nullable();
            $table->char('account_no')->nullable();
            $table->string('account_name')->nullable();
            $table->string('bankbranch')->nullable();
            $table->enum('account_type', ['saving', 'current', 'fixed'])->default('saving');
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
        Schema::dropIfExists('bankaccounts');
    }
}
