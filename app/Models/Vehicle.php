<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'make',
        'model',
        'plate_number',
        'driver_id',
    ];

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }}
