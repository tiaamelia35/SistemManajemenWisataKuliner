<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'description',
        'address',
        'latitude',
        'longitude',
        'image_url',
        'certification_status',
        'is_active',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class);
    }

    // Average Rating Helper
    public function getAverageRatingAttribute()
    {
        $approvedReviews = $this->reviews()->where('status', 'approved');
        if ($approvedReviews->count() === 0) {
            return 0;
        }
        return round($approvedReviews->avg('rating'), 1);
    }

    public function getReviewsCountAttribute()
    {
        return $this->reviews()->where('status', 'approved')->count();
    }
}
