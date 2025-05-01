<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SensorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('sensors')->insert([
            [
                'name' => 'AQI Sensor 1',
                'latitude' => 12.9716,
                'longitude' => 77.5946,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'AQI Sensor 2',
                'latitude' => 28.7041,
                'longitude' => 77.1025,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'AQI Sensor 3',
                'latitude' => 19.0760,
                'longitude' => 72.8777,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}