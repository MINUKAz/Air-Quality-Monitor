<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function index()
    {
        $sensors = Sensor::select([
            'id',
            'name',
            'latitude',
            'longitude',
            'status',
            'last_aqi',
            'last_reading_at',
        ])->get();

        return response()->json($sensors->map(function($sensor) {
            return [
                'id' => $sensor->id,
                'name' => $sensor->name,
                'latitude' => $sensor->latitude,
                'longitude' => $sensor->longitude,
                'status' => $sensor->status,
                'aqi' => $sensor->last_aqi,
                'last_updated' => $sensor->last_reading_at
            ];
        }));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sensors',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'status' => 'required|boolean'
        ]);

        $sensor = Sensor::create($validated);
        return response()->json($sensor, 201);
    }

    public function update(Request $request, Sensor $sensor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sensors,name,' . $sensor->id,
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'status' => 'required|boolean'
        ]);

        $sensor->update($validated);
        return response()->json($sensor);
    }

    public function destroy(Sensor $sensor)
    {
        $sensor->delete();
        return response()->json(['message' => 'Sensor deleted successfully']);
    }
}
