<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = [
        'timestamp',
        'location',
        'aqiLevel',
        'status'
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'aqiLevel' => 'integer'
    ];

    public function getStatusClass()
    {
        $thresholds = Threshold::first();
        
        if ($this->aqiLevel <= $thresholds->moderate) {
            return 'good';
        } elseif ($this->aqiLevel <= $thresholds->unhealthy) {
            return 'warning';
        }
        return 'danger';
    }

    public function getStatusText()
    {
        $thresholds = Threshold::first();
        
        if ($this->aqiLevel <= $thresholds->moderate) {
            return 'Good';
        } elseif ($this->aqiLevel <= $thresholds->unhealthy) {
            return 'Moderate';
        }
        return 'Hazardous';
    }
}