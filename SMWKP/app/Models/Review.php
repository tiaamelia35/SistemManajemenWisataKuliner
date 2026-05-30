<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'tourist_id',
        'restaurant_id',
        'rating',
        'review_text',
        'status',
    ];

    public function tourist()
    {
        return $this->belongsTo(User::class, 'tourist_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
