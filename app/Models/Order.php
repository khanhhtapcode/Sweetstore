<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'driver_id',
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'total_amount',
        'status',
        'payment_method',
        'notes',
        'assigned_at',
        'picked_up_at',
        'delivered_at',
        'delivery_notes'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'assigned_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // Định nghĩa các trạng thái đơn hàng (đã cập nhật)
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PREPARING = 'preparing';
    const STATUS_READY = 'ready';
    const STATUS_ASSIGNED = 'assigned';
    const STATUS_PICKED_UP = 'picked_up';
    const STATUS_DELIVERING = 'delivering';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Chờ xác nhận',
            self::STATUS_CONFIRMED => 'Đã xác nhận',
            self::STATUS_PREPARING => 'Đang chuẩn bị',
            self::STATUS_READY => 'Sẵn sàng giao',
            self::STATUS_ASSIGNED => 'Đã gán tài xế',
            self::STATUS_PICKED_UP => 'Đã lấy hàng',
            self::STATUS_DELIVERING => 'Đang giao hàng',
            self::STATUS_DELIVERED => 'Đã giao',
            self::STATUS_CANCELLED => 'Đã hủy',
        ];
    }

    // Relationship: Một đơn hàng thuộc về một user (có thể null cho guest)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: Một đơn hàng thuộc về một tài xế
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    // Relationship: Một đơn hàng có nhiều sản phẩm qua bảng order_items
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    // Relationship: Một đơn hàng có nhiều order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Alias for backward compatibility
    public function items()
    {
        return $this->orderItems();
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

    // Accessor để lấy thông tin tài xế
    public function getDriverInfoAttribute()
    {
        return $this->driver ? $this->driver->name . ' (' . $this->driver->phone . ')' : 'Chưa gán';
    }

    // Accessor để kiểm tra đơn hàng có thể gán tài xế không
    public function getCanAssignDriverAttribute()
    {
        return in_array($this->status, [self::STATUS_READY, self::STATUS_ASSIGNED]);
    }

    // Accessor để kiểm tra đơn hàng có thể cập nhật trạng thái giao hàng không
    public function getCanUpdateDeliveryStatusAttribute()
    {
        return in_array($this->status, [self::STATUS_ASSIGNED, self::STATUS_PICKED_UP, self::STATUS_DELIVERING]);
    }

    // Accessor để tính thời gian giao hàng
    public function getDeliveryTimeAttribute()
    {
        if ($this->assigned_at && $this->delivered_at) {
            return $this->assigned_at->diffInMinutes($this->delivered_at);
        }
        return null;
    }

    // Accessor để kiểm tra giao hàng đúng hạn
    public function getIsOnTimeDeliveryAttribute()
    {
        if (!$this->delivered_at || !$this->created_at) return null;

        // Giả sử thời gian giao hàng tiêu chuẩn là 2 giờ
        $expectedDeliveryTime = $this->created_at->addHours(2);
        return $this->delivered_at <= $expectedDeliveryTime;
    }

    // Scope để lấy đơn hàng cần gán tài xế
    public function scopeNeedDriver($query)
    {
        return $query->where('status', self::STATUS_READY)->whereNull('driver_id');
    }

    // Scope để lấy đơn hàng đang giao
    public function scopeInDelivery($query)
    {
        return $query->whereIn('status', [self::STATUS_ASSIGNED, self::STATUS_PICKED_UP, self::STATUS_DELIVERING]);
    }

    // Scope để lấy đơn hàng của tài xế
    public function scopeByDriver($query, $driverId)
    {
        return $query->where('driver_id', $driverId);
    }

    // Gán tài xế cho đơn hàng
    public function assignDriver($driver)
    {
        $this->update([
            'driver_id' => $driver->id,
            'status' => self::STATUS_ASSIGNED,
            'assigned_at' => now()
        ]);

        // Cập nhật trạng thái tài xế
        $driver->markAsBusy();

        return $this;
    }

    // Cập nhật trạng thái đã lấy hàng
    public function markAsPickedUp($notes = null)
    {
        $this->update([
            'status' => self::STATUS_PICKED_UP,
            'picked_up_at' => now(),
            'delivery_notes' => $notes
        ]);

        return $this;
    }

    // Cập nhật trạng thái đang giao hàng
    public function markAsDelivering($notes = null)
    {
        $this->update([
            'status' => self::STATUS_DELIVERING,
            'delivery_notes' => $notes
        ]);

        return $this;
    }

    // Cập nhật trạng thái đã giao hàng
    public function markAsDelivered($notes = null)
    {
        $this->update([
            'status' => self::STATUS_DELIVERED,
            'delivered_at' => now(),
            'delivery_notes' => $notes
        ]);

        // Cập nhật thống kê tài xế
        if ($this->driver) {
            $this->driver->increment('total_deliveries');
            $this->driver->updateRating();

            // Kiểm tra xem tài xế còn đơn nào đang giao không
            if ($this->driver->currentOrders()->count() === 0) {
                $this->driver->markAsAvailable();
            }
        }

        return $this;
    }

    // Hủy gán tài xế
    public function unassignDriver()
    {
        $driver = $this->driver;

        $this->update([
            'driver_id' => null,
            'status' => self::STATUS_READY,
            'assigned_at' => null,
            'picked_up_at' => null,
            'delivery_notes' => null
        ]);

        // Cập nhật trạng thái tài xế nếu không còn đơn nào
        if ($driver && $driver->currentOrders()->count() === 0) {
            $driver->markAsAvailable();
        }

        return $this;
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
    public function driverRating()
    {
        return $this->hasOne(DriverRating::class, 'order_id', 'id'); // Quan hệ với đánh giá
    }
}
