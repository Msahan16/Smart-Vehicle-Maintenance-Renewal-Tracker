<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'driver_license_number',
        'driver_license_expiry',
        'driver_license_front',
        'driver_license_back',
        'profile_photo',
        'email_notifications',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'driver_license_expiry' => 'date',
            'password' => 'hashed',
            'email_notifications' => 'boolean',
        ];
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
