<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone_number',
        'profile_photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role check helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isTourist(): bool
    {
        return $this->role === 'tourist';
    }

    // Relationships
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class, 'owner_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'tourist_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'tourist_id');
    }

    public function logs()
    {
        return $this->hasMany(Log::class, 'user_id');
    }
}
