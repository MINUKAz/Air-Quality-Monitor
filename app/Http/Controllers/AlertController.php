<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Threshold;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AlertController extends Controller
{
    public function index()
    {
        $alerts = Alert::orderBy('timestamp', 'desc')->get();
        $thresholds = Threshold::first() ?? Threshold::create([
            'moderate' => 50,
            'unhealthy' => 100,
            'hazardous' => 150
        ]);

        return view('alerts', compact('alerts', 'thresholds'));
    }

    public function getAlerts()
    {
        $alerts = Alert::orderBy('timestamp', 'desc')
            ->take(100)
            ->get()
            ->map(function ($alert) {
                return [
                    'timestamp' => $alert->timestamp->format('Y-m-d H:i:s'),
                    'location' => $alert->location,
                    'aqiLevel' => $alert->aqiLevel,
                    'status' => [
                        'class' => $alert->getStatusClass(),
                        'text' => $alert->getStatusText()
                    ]
                ];
            });

        return response()->json($alerts);
    }

    public function getThresholds()
    {
        $thresholds = Threshold::first() ?? Threshold::create([
            'moderate' => 50,
            'unhealthy' => 100,
            'hazardous' => 150
        ]);

        return response()->json($thresholds);
    }

    public function setThresholds(Request $request)
    {
        $validated = $request->validate([
            'moderate' => 'required|integer|min:0|lt:unhealthy',
            'unhealthy' => 'required|integer|min:0|lt:hazardous',
            'hazardous' => 'required|integer|min:0'
        ]);

        $thresholds = Threshold::first() ?? new Threshold();
        $thresholds->fill($validated);
        $thresholds->save();

        return response()->json(['message' => 'Thresholds updated successfully']);
    }
}