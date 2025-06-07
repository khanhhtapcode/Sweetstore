<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2'
    ];

    // Relationship: OrderItem thuộc về một Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relationship: OrderItem thuộc về một Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessor để tính tổng tiền cho item này
    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->price;
    }

    // Accessor để format giá
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.') . ' VNĐ';
    }

    // Accessor để format tổng tiền
    public function getFormattedTotalAttribute()
    {
        return number_format($this->total_price, 0, ',', '.') . ' VNĐ';
    }
}
