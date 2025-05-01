<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropForeignKeys extends Migration
{
    public function up()
    {
        if (Schema::hasTable('sensor_readings')) {
            Schema::table('sensor_readings', function (Blueprint $table) {
                $table->dropForeign(['sensor_id']);
            });
        }

        if (Schema::hasTable('aqi_data')) {
            Schema::table('aqi_data', function (Blueprint $table) {
                $table->dropForeign(['sensor_id']);
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('sensor_readings')) {
            Schema::table('sensor_readings', function (Blueprint $table) {
                $table->foreign('sensor_id')->references('id')->on('sensors')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('aqi_data')) {
            Schema::table('aqi_data', function (Blueprint $table) {
                $table->foreign('sensor_id')->references('id')->on('sensors')->onDelete('cascade');
            });
        }
    }
}