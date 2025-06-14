<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DriverRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'driver_id',
        'order_id',
        'rating',
        'comment',
        'rated_at',
    ];

    protected $casts = [
        'rated_at' => 'datetime',
    ];

    /**
     * Lấy thông tin người dùng đã đánh giá.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Lấy thông tin tài xế được đánh giá.
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    /**
     * Lấy thông tin đơn hàng liên quan.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // XÓA METHOD updateRating() - Đây là nguyên nhân gây lỗi
}
