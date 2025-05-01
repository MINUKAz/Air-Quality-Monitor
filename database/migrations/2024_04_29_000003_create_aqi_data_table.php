<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('aqi_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sensor_id')->constrained()->onDelete('cascade');
            $table->float('aqi_value');
            $table->string('category');
            $table->timestamp('measurement_time');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aqi_data');
    }
};