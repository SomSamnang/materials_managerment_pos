<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'invoice_number',
        'total_amount_usd',
        'total_amount_khr',
        'status',
        'issued_date',
        'due_date',
    ];

    protected $dates = ['issued_date', 'due_date'];

    /**
     * Relation with order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Generate invoice number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (empty($invoice->invoice_number)) {
                $invoice->invoice_number = 'INV-' . date('Y') . '-' . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
