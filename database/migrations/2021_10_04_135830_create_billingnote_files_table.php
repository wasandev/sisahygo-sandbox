<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingnoteFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billingnote_files', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('billingnote_id')->unsigned();
            $table->string('billingnote_files');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
        });
        Schema::table('billingnote_files', function (Blueprint $table) {
            $table->foreign('billingnote_id')
                ->references('id')
                ->on('billingnotes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billingnote_files');
    }
}
