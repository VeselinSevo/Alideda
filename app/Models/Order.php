<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'subtotal',
        'total',
        'full_name',
        'email',
        'phone',
        'address',
        'city',
        'country_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function storeOrders()
    {
        return $this->hasMany(StoreOrder::class);
    }
}
