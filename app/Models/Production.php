<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'remaining_quantity',
        'production_date',
        'expiry_date',
        'notes',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
