<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'type',
        'title',
        'message',
        'due_date',
        'is_read',
        'email_sent',
        'email_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'is_read' => 'boolean',
            'email_sent' => 'boolean',
            'email_sent_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
