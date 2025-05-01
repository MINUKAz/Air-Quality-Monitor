<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('alerts')) {
            Schema::create('alerts', function (Blueprint $table) {
                $table->id();
                $table->timestamp('timestamp')->useCurrent();
                $table->string('location');
                $table->integer('aqiLevel');
                $table->string('status');
                $table->timestamps();
                
                // Add index for better performance
                $table->index('timestamp');
                $table->index('location');
            });
        } else {
            Schema::table('alerts', function (Blueprint $table) {
                // Add any missing columns
                if (!Schema::hasColumn('alerts', 'timestamp')) {
                    $table->timestamp('timestamp')->useCurrent();
                }
                if (!Schema::hasColumn('alerts', 'location')) {
                    $table->string('location');
                }
                if (!Schema::hasColumn('alerts', 'aqiLevel')) {
                    $table->integer('aqiLevel');
                }
                if (!Schema::hasColumn('alerts', 'status')) {
                    $table->string('status');
                }
                
                // Add indexes if they don't exist
                if (!Schema::hasIndex('alerts', 'alerts_timestamp_index')) {
                    $table->index('timestamp');
                }
                if (!Schema::hasIndex('alerts', 'alerts_location_index')) {
                    $table->index('location');
                }
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('alerts');
    }
};