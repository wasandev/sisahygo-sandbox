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
        Schema::create('api_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('customer_id');
            $table->string('api_key',255)->nullable(); // Store hashed API key
            $table->tinyInteger('is_active')->default(1); // 1 for active, 0 for inactive
            $table->string('allowed_ips')->nullable(); // Comma-separated list of allowed IPs
            $table->integer('rate_limit_per_minute')->default(60); // Default rate limit
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
        Schema::dropIfExists('api_clients');
    }
};
