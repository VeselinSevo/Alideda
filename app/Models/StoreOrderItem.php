<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreOrderItem extends Model
{
    protected $fillable = ['store_order_id', 'product_id', 'quantity', 'price'];

    public function storeOrder()
    {
        return $this->belongsTo(StoreOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}