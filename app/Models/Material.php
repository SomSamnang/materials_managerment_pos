<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\StockMovement;
use App\Models\Order;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materials';

    protected $fillable = [
        'code',
        'name',
        'stock',
        'min_stock',
        'price',
        'total_usd',
        'total_riel',
        'image',
        'description',
        'status',
    ];

    /**
     * Stock movement history
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Relation with orders (many-to-many)
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_material')->withPivot('quantity', 'unit_price_usd');
    }

    /**
     * Safe stock getter
     */
    public function currentStock()
    {
        return $this->stock ?? 0;
    }
}
