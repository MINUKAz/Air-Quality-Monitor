<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SimulationSetting;
use Illuminate\Http\Request;

class SystemOverviewController extends Controller
{
    public function index()
    {
        $simulationSettings = SimulationSetting::first();
        
        if (!$simulationSettings) {
            $simulationSettings = SimulationSetting::create([
                'frequency' => 5,
                'baseline' => 50,
                'variation' => 10,
                'is_running' => false
            ]);
        }
        
        $systemData = [
            'activeSensors' => Sensor::where('status', 1)->count(),
            'systemHealth' => $simulationSettings->is_running ? rand(95, 100) : rand(60, 94),
            'activeAlerts' => $simulationSettings->is_running ? rand(0, 3) : rand(2, 5),
            'sensors' => Sensor::latest()->get()->map(function($sensor) {
                return [
                    'id' => $sensor->name,
                    'status' => $sensor->status == 1 ? 'active' : 'inactive',
                    'healthLevel' => $sensor->status == 1 ? 'good' : 'inactive'
                ];
            }),
            'recentActivity' => $this->getRecentActivity($simulationSettings)
        ];

        return view('system-overview', compact('systemData'));
    }

    private function getRecentActivity($settings)
    {
        $data = [];
        $date = now()->subDays(6);
        
        for ($i = 0; $i < 7; $i++) {
            $data[] = [
                'date' => $date->format('Y-m-d'),
                'value' => $settings->is_running 
                    ? rand($settings->baseline - $settings->variation, $settings->baseline + $settings->variation)
                    : rand(20, 40)
            ];
            $date->addDay();
        }
        
        return $data;
    }

    /**
     * Get real-time dashboard statistics
     */
    public function getStats()
    {
        return response()->json([
            'activeSensors' => Sensor::where('status', 1)->count(),
            'systemHealth' => 98, // You can calculate this based on your needs
            'activeAlerts' => 3   // Replace with actual alerts count from your system
        ]);
    }
}