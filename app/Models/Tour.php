<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $fillable = [
        'user_id',
        'vehicle_id',
        'tour_name',
        'description',
        'start_mileage',
        'end_mileage',
        'fuel_amount',
        'refueled_place',
        'fuel_bill_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }}
