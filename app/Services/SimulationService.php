<?php

namespace App\Services;

use App\Models\Sensor;
use App\Models\SimulationSetting;

class SimulationService
{
    public function getOrGenerateAQIData($sensorId)
    {
        $sensor = Sensor::find($sensorId);
        $settings = SimulationSetting::first();

        if (!$sensor || !$settings) {
            return null; // Or handle the error as needed
        }

        if ($settings->is_running) {
            // Generate new AQI data
            $baseline = $settings->baseline;
            $variation = $settings->variation;
            $aqi = $this->generateAQI($baseline, $variation);

            // Update the sensor's last_aqi
            $sensor->update(['last_aqi' => $aqi]);

            return [
                'aqi' => $aqi,
                'timestamp' => now(),
            ];
        } else {
            // Retrieve existing AQI data
            return [
                'aqi' => $sensor->last_aqi,
                'timestamp' => $sensor->updated_at,
            ];
        }
    }

    private function generateAQI($baseline, $variation)
    {
        $min = $baseline - $variation;
        $max = $baseline + $variation;
        return rand($min, $max);
    }
}