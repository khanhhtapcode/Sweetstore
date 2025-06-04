<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'image_url',
        'stock_quantity',
        'is_featured',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationship: Một sản phẩm thuộc về một danh mục
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship: Một sản phẩm có thể có nhiều đơn hàng
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    // Scope để lấy sản phẩm đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope để lấy sản phẩm nổi bật
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Accessor để format giá
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.') . ' VNĐ';
    }
}
