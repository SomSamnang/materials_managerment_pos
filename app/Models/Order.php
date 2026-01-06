<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total_amount_usd',
        'total_amount_khr',
        'notes',
    ];

    /**
     * Relation with user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation with materials (many-to-many)
     */
    public function materials()
    {
        return $this->belongsToMany(Material::class, 'order_material')->withPivot('quantity', 'unit_price_usd');
    }

    /**
     * Relation with invoice
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}