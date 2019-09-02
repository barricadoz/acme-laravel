<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    public function orderDetails() {
        return $this->hasMany('App\OrderDetail');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
