<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'total_amount',
        'status',
        'notes'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    // Định nghĩa các trạng thái đơn hàng
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PREPARING = 'preparing';
    const STATUS_READY = 'ready';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Chờ xác nhận',
            self::STATUS_CONFIRMED => 'Đã xác nhận',
            self::STATUS_PREPARING => 'Đang chuẩn bị',
            self::STATUS_READY => 'Sẵn sàng giao',
            self::STATUS_DELIVERED => 'Đã giao',
            self::STATUS_CANCELLED => 'Đã hủy',
        ];
    }

    // Relationship: Một đơn hàng thuộc về một user (có thể null cho guest)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: Một đơn hàng có nhiều sản phẩm
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    // Accessor để lấy tên trạng thái
    public function getStatusNameAttribute()
    {
        $statuses = self::getStatuses();
        return $statuses[$this->status] ?? 'Không xác định';
    }

    // Accessor để format tổng tiền
    public function getFormattedTotalAttribute()
    {
        return number_format($this->total_amount, 0, ',', '.') . ' VNĐ';
    }

    // Boot method để tự động tạo order number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = 'ORD' . date('YmdHis') . rand(1000, 9999);
            }
        });
    }
}
