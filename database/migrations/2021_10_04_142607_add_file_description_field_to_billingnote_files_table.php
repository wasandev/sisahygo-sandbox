<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileDescriptionFieldToBillingnoteFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billingnote_files', function (Blueprint $table) {
            $table->string('file_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('billingnote_files', function (Blueprint $table) {
            $table->dropColumn('file_description');
        });
    }
}
