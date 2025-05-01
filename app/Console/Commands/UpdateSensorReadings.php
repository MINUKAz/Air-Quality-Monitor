<?php

namespace App\Console\Commands;

use App\Models\Sensor;
use App\Models\AqiData;
use App\Services\AQISimulationService;
use Illuminate\Console\Command;

class UpdateSensorReadings extends Command
{
    protected $signature = 'sensors:update-readings';
    protected $description = 'Update AQI readings for all active sensors';

    protected $simulationService;

    public function __construct(AQISimulationService $simulationService)
    {
        parent::__construct();
        $this->simulationService = $simulationService;
    }

    public function handle()
    {
        $settings = \App\Models\SimulationSetting::first();
        
        if (!$settings || !$settings->is_running) {
            $this->info('Simulation is not running');
            return;
        }

        Sensor::where('status', 1)->each(function ($sensor) use ($settings) {
            $aqiData = $this->simulationService->getOrGenerateAQIData($sensor->id);
            $this->info("Updated sensor {$sensor->name} with AQI value: {$aqiData['value']}");
        });

        $this->info('Sensor readings update completed');
    }
}