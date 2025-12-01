<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'service_type',
        'service_date',
        'next_due_date',
        'mileage',
        'service_center',
        'cost',
        'notes',
        'invoice_image',
        'service_bill',
        'related_document',
    ];

    protected function casts(): array
    {
        return [
            'service_date' => 'date',
            'next_due_date' => 'date',
            'cost' => 'decimal:2',
        ];
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
