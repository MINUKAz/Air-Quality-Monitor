<?php

namespace Database\Seeders;

use App\Models\SimulationSetting;
use Illuminate\Database\Seeder;

class SimulationSettingsSeeder extends Seeder
{
    public function run(): void
    {
        SimulationSetting::create([
            'frequency' => 5,
            'baseline' => 50,
            'variation' => 10,
            'is_running' => false
        ]);
    }
}