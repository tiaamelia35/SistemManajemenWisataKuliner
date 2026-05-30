<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'type',
        'certificate_number',
        'issued_by',
        'expiry_date',
        'certificate_file',
        'status',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
