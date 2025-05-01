<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SimulationSetting;
use App\Models\AqiData;
use App\Services\AQISimulationService;
use Illuminate\Http\Request;

class SimulationController extends Controller
{
    protected $simulationService;

    public function __construct(AQISimulationService $simulationService)
    {
        $this->simulationService = $simulationService;
    }

    public function index()
    {
        $settings = SimulationSetting::first() ?? SimulationSetting::create([
            'frequency' => 60,
            'baseline' => 50,
            'variation' => 10,
            'is_running' => false
        ]);

        $sensors = Sensor::all();

        return view('simulation-management', [
            'settings' => $settings,
            'sensors' => $sensors,
            'simulationService' => $this->simulationService
        ]);
    }

    public function updateConfig(Request $request)
    {
        $settings = SimulationSetting::first();
        $settings->update($request->only(['frequency', 'baseline', 'variation']));
        return response()->json($settings);
    }

    public function start()
    {
        $settings = SimulationSetting::first();
        $settings->update(['is_running' => true]);

        // Generate initial AQI values for all sensors
        $sensors = Sensor::all();
        foreach ($sensors as $sensor) {
            $aqi = $this->generateAQI($settings->baseline, $settings->variation);
            $sensor->update([
                'last_aqi' => $aqi,
                'last_reading_at' => now()
            ]);
        }

        return response()->json(['status' => 'started']);
    }

    private function generateAQI($baseline, $variation)
    {
        $min = $baseline - $variation;
        $max = $baseline + $variation;
        return rand(max(0, $min), min(500, $max));
    }

    public function stop()
    {
        $settings = SimulationSetting::first();
        $settings->update(['is_running' => false]);
        return response()->json(['status' => 'stopped']);
    }

    public function updateAQI(Request $request)
    {
        $settings = SimulationSetting::first();
        
        if (!$settings->is_running) {
            return response()->json(['message' => 'Simulation is not running'], 400);
        }

        $baseline = $request->input('baseline', $settings->baseline);
        $variation = $request->input('variation', $settings->variation);

        // Update AQI for all active sensors
        $sensors = Sensor::where('status', true)->get();
        foreach ($sensors as $sensor) {
            $aqi = $this->generateAQI($baseline, $variation);
            $sensor->update([
                'last_aqi' => $aqi,
                'last_reading_at' => now()
            ]);

            // Create historical record
            AqiData::create([
                'sensor_id' => $sensor->id,
                'aqi_value' => $aqi,
                'category' => $this->getAQICategory($aqi),
                'measurement_time' => now()
            ]);
        }

        return response()->json(['message' => 'AQI values updated']);
    }

    private function getAQICategory($aqi)
    {
        if ($aqi <= 50) return 'Good';
        if ($aqi <= 100) return 'Moderate';
        if ($aqi <= 150) return 'Unhealthy for Sensitive Groups';
        if ($aqi <= 200) return 'Unhealthy';
        if ($aqi <= 300) return 'Very Unhealthy';
        return 'Hazardous';
    }
}
