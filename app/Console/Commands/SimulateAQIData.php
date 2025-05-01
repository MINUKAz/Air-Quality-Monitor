<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sensor;
use App\Models\AqiData;

class SimulateAQIData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simulate:aqi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate simulated AQI data for sensors';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $sensors = Sensor::all(); // Fetch all sensors
        foreach ($sensors as $sensor) {
            AqiData::create([
                'sensor_id' => $sensor->id,
                'value' => rand(0, 300), // Simulate AQI values (0-300)
                'timestamp' => now(),
            ]);
        }
        $this->info('Simulated AQI data generated!');
    }
}