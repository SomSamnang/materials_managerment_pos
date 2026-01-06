<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id', 'quantity', 'unit_cost', 'total_cost', 'purchase_date', 'supplier', 'notes'
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}