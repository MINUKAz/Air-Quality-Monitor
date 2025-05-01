<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('simulation_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('frequency')->default(5);    // minutes
            $table->integer('baseline')->default(50);    // AQI base
            $table->integer('variation')->default(10);   // ± range
            $table->boolean('is_running')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simulation_settings');
    }
};
