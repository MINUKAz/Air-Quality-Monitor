<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Threshold extends Model
{
    protected $fillable = [
        'moderate',
        'unhealthy',
        'hazardous'
    ];

    protected $casts = [
        'moderate' => 'integer',
        'unhealthy' => 'integer',
        'hazardous' => 'integer'
    ];
}