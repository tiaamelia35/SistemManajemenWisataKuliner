<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'details',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Helper method to write log entries dynamically.
     */
    public static function write($userId, string $action, ?string $details = null): self
    {
        return self::create([
            'user_id' => $userId,
            'action' => $action,
            'details' => $details,
            'ip_address' => Request::ip(),
        ]);
    }
}
