<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('sensor_readings')) {
            Schema::create('sensor_readings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sensor_id')->constrained()->onDelete('cascade');
                $table->float('value');
                $table->timestamp('reading_time');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('sensor_readings');
    }
};