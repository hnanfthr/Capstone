<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_no',
        'customer_name',
        'whatsapp_number',
        'address',
        'status',
        'total_price',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->order_no)) {
                $latestOrder = static::latest('id')->first();
                $nextId = $latestOrder ? $latestOrder->id + 1 : 1;
                // Default format: HK-YYYYMMDD-0001 (bisa disesuaikan nanti)
                $model->order_no = 'HK-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
