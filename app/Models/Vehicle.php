<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_number',
        'brand',
        'model',
        'fuel_type',
        'engine_capacity',
        'manufactured_year',
        'color',
        'photo',
        'license_expiry',
        'insurance_expiry',
        'emission_test_expiry',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'license_expiry' => 'date',
            'insurance_expiry' => 'date',
            'emission_test_expiry' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function maintenanceRecords()
    {
        return $this->hasMany(MaintenanceRecord::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
