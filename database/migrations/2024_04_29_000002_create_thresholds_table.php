<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('thresholds', function (Blueprint $table) {
            $table->id();
            $table->integer('moderate')->default(50);
            $table->integer('unhealthy')->default(100);
            $table->integer('hazardous')->default(150);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('thresholds');
    }
};