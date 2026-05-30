<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'tourist_id',
        'restaurant_id',
        'name',
        'phone_number',
        'booking_date',
        'pax_count',
        'status',
    ];

    protected $casts = [
        'booking_date' => 'datetime',
    ];

    public function tourist()
    {
        return $this->belongsTo(User::class, 'tourist_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'booking_menus')
                    ->withPivot('quantity', 'price_at_booking')
                    ->withTimestamps();
    }

    // Dynamic Total Price Calculator
    public function getTotalAttribute()
    {
        return $this->menus->sum(function ($menu) {
            return $menu->pivot->quantity * $menu->pivot->price_at_booking;
        });
    }
}
