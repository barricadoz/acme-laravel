<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id', 'product_id', 'quantity', 'unit_price', 'total'
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
