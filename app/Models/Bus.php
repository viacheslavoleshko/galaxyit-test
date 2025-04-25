<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bus extends Model
{
    protected $fillable = [
        'brand_id',
        'user_id',
        'license_plate',
    ];

    protected static function booted(): void
    {
        static::saving(function ($brand) {
            $brand->license_plate = Str::upper($brand->license_plate);
        });
    }

    public function user(): BelongsTo 
    {
        return $this->belongsTo(User::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
