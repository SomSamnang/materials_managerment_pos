<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get the exchange rate, defaulting to 4100 if not set.
     * Uses caching to reduce database queries.
     */
    public static function getExchangeRate()
    {
        // Cache the rate for 1 hour (3600 seconds)
        return Cache::remember('exchange_rate', 3600, function () {
            return (float) self::where('key', 'exchange_rate')->value('value') ?: 4100;
        });
    }
}