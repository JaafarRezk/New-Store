<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;
    protected $fillable=[
        'price',
        'quantity',
        'total_price',
        'variation_id',
        'order_id',
    ];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function variations()
    {
        return $this->belongsTo(Variation::class);
    }
}
