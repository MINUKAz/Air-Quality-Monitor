<?php

namespace App\Http\Controllers;

use App\Models\AqiData;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HistoricalDataController extends Controller
{
    public function index()
    {
        return view('historical-data');
    }

    public function getData($timeRange)
    {
        $end = Carbon::now();
        $start = match($timeRange) {
            '24h' => $end->copy()->subDay(),
            '7d' => $end->copy()->subWeek(),
            '30d' => $end->copy()->subMonth(),
            default => $end->copy()->subDay(),
        };

        $data = AqiData::whereBetween('timestamp', [$start, $end])
            ->orderBy('timestamp')
            ->get();

        return response()->json([
            'labels' => $data->pluck('timestamp')->map(fn($date) => $date->format('Y-m-d H:i')),
            'values' => $data->pluck('aqi_value')
        ]);
    }

    public function export($timeRange)
    {
        $end = Carbon::now();
        $start = match($timeRange) {
            '24h' => $end->copy()->subDay(),
            '7d' => $end->copy()->subWeek(),
            '30d' => $end->copy()->subMonth(),
            default => $end->copy()->subDay(),
        };

        $data = AqiData::whereBetween('timestamp', [$start, $end])
            ->orderBy('timestamp')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=aqi_data.csv',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Timestamp', 'AQI Value', 'Location']);
            
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->timestamp,
                    $row->aqi_value,
                    $row->location
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}