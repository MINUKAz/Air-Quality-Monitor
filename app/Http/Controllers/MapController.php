<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\AqiData;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        $sensors = Sensor::with(['aqiData' => function($query) {
            $query->latest('measurement_time')->limit(1);
        }])->get();

        return view('map', compact('sensors'));
    }

    public function getSensors()
    {
        $sensors = Sensor::with(['aqiData' => function($query) {
            $query->latest('measurement_time')->first();
        }])->get()->map(function ($sensor) {
            $latestAqi = $sensor->aqiData->first();
            
            return [
                'id' => $sensor->id,
                'name' => $sensor->name,
                'latitude' => $sensor->latitude,
                'longitude' => $sensor->longitude,
                'status' => $sensor->status,
                'aqi' => $latestAqi ? $latestAqi->aqi_value : null,
                'timestamp' => $latestAqi ? $latestAqi->measurement_time : null,
                'is_real_time' => true
            ];
        });

        return response()->json($sensors);
    }
}