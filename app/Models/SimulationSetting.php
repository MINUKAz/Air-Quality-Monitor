<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimulationSetting extends Model
{
    protected $fillable = [
        'frequency',
        'baseline',
        'variation',
        'is_running'
    ];
}