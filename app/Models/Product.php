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
        'image_url',
        'category_id',
        'stock_quantity',
        'is_active',
        'is_featured'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'stock_quantity' => 'integer',
    ];

    /**
     * Relationship với Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship với Orders thông qua pivot table
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    /**
     * Scope cho sản phẩm đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope cho sản phẩm nổi bật
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope cho sản phẩm sắp hết hàng
     */
    public function scopeLowStock($query, $threshold = 5)
    {
        return $query->where('stock_quantity', '<=', $threshold);
    }

    /**
     * Accessor cho formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.') . ' VNĐ';
    }

    /**
     * Check if product is in stock
     */
    public function isInStock()
    {
        return $this->stock_quantity > 0;
    }

    /**
     * Check if product is low stock
     */
    public function isLowStock($threshold = 5)
    {
        return $this->stock_quantity <= $threshold && $this->stock_quantity > 0;
    }

    /**
     * Check if product is out of stock
     */
    public function isOutOfStock()
    {
        return $this->stock_quantity <= 0;
    }

    /**
     * Decrease stock quantity
     */
    public function decreaseStock($quantity)
    {
        if ($this->stock_quantity >= $quantity) {
            $this->decrement('stock_quantity', $quantity);
            return true;
        }
        return false;
    }

    /**
     * Increase stock quantity
     */
    public function increaseStock($quantity)
    {
        $this->increment('stock_quantity', $quantity);
        return true;
    }

    /**
     * Set stock quantity
     */
    public function setStock($quantity)
    {
        $this->update(['stock_quantity' => $quantity]);
        return true;
    }
}
