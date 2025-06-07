<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_code',
        'name',
        'email',
        'phone',
        'address',
        'license_number',
        'license_expiry',
        'vehicle_type',
        'vehicle_number',
        'status',
        'rating',
        'total_deliveries',
        'notes',
        'last_active_at'
    ];

    protected $casts = [
        'license_expiry' => 'date',
        'rating' => 'decimal:2',
        'last_active_at' => 'datetime',
    ];

    // Định nghĩa các trạng thái tài xế
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_BUSY = 'busy';

    // Định nghĩa các loại xe
    const VEHICLE_MOTORBIKE = 'motorbike';
    const VEHICLE_SMALL_TRUCK = 'small_truck';
    const VEHICLE_VAN = 'van';

    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE => 'Sẵn sàng',
            self::STATUS_INACTIVE => 'Không hoạt động',
            self::STATUS_BUSY => 'Đang bận',
        ];
    }

    public static function getVehicleTypes()
    {
        return [
            self::VEHICLE_MOTORBIKE => 'Xe máy',
            self::VEHICLE_SMALL_TRUCK => 'Xe tải nhỏ',
            self::VEHICLE_VAN => 'Xe van',
        ];
    }

    // Relationship: Một tài xế có nhiều đơn hàng
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Relationship: Đơn hàng hiện tại đang giao
    public function currentOrders()
    {
        return $this->hasMany(Order::class)->whereIn('status', ['assigned', 'picked_up', 'delivering']);
    }

    // Relationship: Đơn hàng đã hoàn thành
    public function completedOrders()
    {
        return $this->hasMany(Order::class)->where('status', 'delivered');
    }

    // Accessor để lấy tên trạng thái
    public function getStatusNameAttribute()
    {
        $statuses = self::getStatuses();
        return $statuses[$this->status] ?? 'Không xác định';
    }

    // Accessor để lấy tên loại xe
    public function getVehicleTypeNameAttribute()
    {
        $types = self::getVehicleTypes();
        return $types[$this->vehicle_type] ?? 'Không xác định';
    }

    // Accessor để format rating
    public function getFormattedRatingAttribute()
    {
        return number_format($this->rating, 1) . '/5.0';
    }

    // Accessor để kiểm tra bằng lái hết hạn
    public function getIsLicenseExpiredAttribute()
    {
        return $this->license_expiry && $this->license_expiry->isPast();
    }

    // Accessor để kiểm tra bằng lái sắp hết hạn (trong 30 ngày)
    public function getIsLicenseExpiringSoonAttribute()
    {
        return $this->license_expiry && $this->license_expiry->diffInDays(now()) <= 30 && !$this->is_license_expired;
    }

    // Accessor để lấy số ngày đến hạn bằng lái
    public function getDaysToLicenseExpiryAttribute()
    {
        if (!$this->license_expiry) return null;

        $days = $this->license_expiry->diffInDays(now());
        return $this->license_expiry->isPast() ? -$days : $days;
    }

    // Scope để lấy tài xế đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    // Scope để lấy tài xế có thể nhận đơn (active và không busy)
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    // Scope để lấy tài xế đang bận
    public function scopeBusy($query)
    {
        return $query->where('status', self::STATUS_BUSY);
    }

    // Boot method để tự động tạo driver code
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($driver) {
            if (!$driver->driver_code) {
                $driver->driver_code = self::generateDriverCode();
            }
        });
    }

    // Tạo mã tài xế tự động
    public static function generateDriverCode()
    {
        $lastDriver = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastDriver ? (int) substr($lastDriver->driver_code, 2) : 0;
        $newNumber = $lastNumber + 1;

        return 'DR' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    // Cập nhật trạng thái bận
    public function markAsBusy()
    {
        $this->update([
            'status' => self::STATUS_BUSY,
            'last_active_at' => now()
        ]);
    }

    // Cập nhật trạng thái sẵn sàng
    public function markAsAvailable()
    {
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'last_active_at' => now()
        ]);
    }

    // Cập nhật rating sau khi hoàn thành đơn hàng
    public function updateRating()
    {
        $completedOrders = $this->completedOrders()->count();
        if ($completedOrders > 0) {
            // Logic tính rating dựa trên hiệu suất
            // Có thể mở rộng thêm dựa trên feedback từ khách hàng
            $onTimeDeliveries = $this->completedOrders()
                ->whereRaw('delivered_at <= DATE_ADD(created_at, INTERVAL 2 HOUR)')
                ->count();

            $rating = ($onTimeDeliveries / $completedOrders) * 5;
            $this->update(['rating' => $rating]);
        }
    }

    // Kiểm tra xem tài xế có thể nhận thêm đơn không
    public function canTakeNewOrder()
    {
        return $this->status === self::STATUS_ACTIVE &&
            $this->currentOrders()->count() < 3 && // Giới hạn tối đa 3 đơn cùng lúc
            !$this->is_license_expired;
    }

    // Lấy số đơn hàng đang giao
    public function getCurrentOrdersCountAttribute()
    {
        return $this->currentOrders()->count();
    }
}
