<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sensors', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('sensors', 'last_aqi')) {
                $table->integer('last_aqi')->default(0);
            }
            if (!Schema::hasColumn('sensors', 'last_reading_at')) {
                $table->timestamp('last_reading_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sensors', function (Blueprint $table) {
            $table->dropColumn(['last_aqi', 'last_reading_at']);
        });
    }
};
