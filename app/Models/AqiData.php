<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AqiData extends Model
{
    use HasFactory;

    protected $fillable = [
        'sensor_id',
        'aqi_value',
        'category',
        'measurement_time'
    ];

    protected $casts = [
        'aqi_value' => 'float',
        'measurement_time' => 'datetime'
    ];

    public $timestamps = false;

    public function sensor(): BelongsTo
    {
        return $this->belongsTo(Sensor::class);
    }
}