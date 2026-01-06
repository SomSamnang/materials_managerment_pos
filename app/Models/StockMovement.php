<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Material;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'type',       // 'in' or 'out'
        'quantity',
        'description'
    ];

    /**
     * Relation to material
     */
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
