<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image_url',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship: Một danh mục có nhiều sản phẩm
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Scope để lấy danh mục đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
