<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_AWAITING_BANK = 'awaiting_bank_transfer';
    const STATUS_CASH_ON_DELIVERY = 'cash_on_delivery';

    protected $fillable = [
        'user_id',
        'temp_order_id',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'postcode',
        'order_note',
        'subtotal',
        'total',
       'status',
    'payment_method',
    'payment_screenshot',
    'payment_intent',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function products()
{
    return $this->belongsToMany(Product::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}


    
}
