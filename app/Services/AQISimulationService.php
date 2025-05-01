<?php

namespace App\Services;

use App\Models\AqiData;
use App\Models\Sensor;

class AQISimulationService
{
    public function getOrGenerateAQIData($sensorId)
    {
        // Get the latest AQI data using the correct column name
        $latestAqi = AqiData::where('sensor_id', $sensorId)
            ->orderBy('measurement_time', 'desc')
            ->first();

        if (!$latestAqi) {
            // Generate new AQI data if none exists
            $latestAqi = $this->generateAQIData($sensorId);
        }

        return $latestAqi;
    }

    private function generateAQIData($sensorId)
    {
        return AqiData::create([
            'sensor_id' => $sensorId,
            'aqi_value' => rand(0, 500),
            'category' => $this->getAQICategory(rand(0, 500)),
            'measurement_time' => now()
        ]);
    }

    private function getAQICategory($value)
    {
        if ($value <= 50) return 'Good';
        if ($value <= 100) return 'Moderate';
        if ($value <= 150) return 'Unhealthy for Sensitive Groups';
        if ($value <= 200) return 'Unhealthy';
        if ($value <= 300) return 'Very Unhealthy';
        return 'Hazardous';
    }
}