<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'status',
        'last_aqi',
        'last_reading_at'
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'status' => 'boolean',
        'last_aqi' => 'integer',
        'last_reading_at' => 'datetime'
    ];

    public function aqiData()
    {
        return $this->hasMany(AqiData::class);
    }
}