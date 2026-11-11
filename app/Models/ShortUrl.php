<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShortUrl extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'user_id',
        'original_url',
        'short_code',
        'visits',
    ];

    /**
     * Get the company that owns the short URL.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user who created the short URL.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
