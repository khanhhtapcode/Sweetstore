<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        $products = [
            // Bánh Kem
            [
                'name' => 'Bánh Kem Chocolate Deluxe',
                'description' => 'Bánh kem chocolate 3 lớp với kem tươi và chocolate Bỉ cao cấp',
                'price' => 350000,
                'category_id' => 1,
                'image_url' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=500',
                'stock_quantity' => 15,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Bánh Kem Strawberry Classic',
                'description' => 'Bánh kem dâu tây tươi với kem vanilla và dâu tây tự nhiên',
                'price' => 320000,
                'category_id' => 1,
                'image_url' => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=500',
                'stock_quantity' => 12,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Bánh Kem Tiramisu',
                'description' => 'Bánh kem tiramisu Ý với mascarpone và cà phê espresso',
                'price' => 380000,
                'category_id' => 1,
                'image_url' => 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?w=500',
                'stock_quantity' => 8,
                'is_featured' => false,
                'is_active' => true,
            ],

            // Cupcakes
            [
                'name' => 'Cupcake Vanilla Rainbow',
                'description' => 'Set 6 cupcake vanilla với kem bơ nhiều màu sắc',
                'price' => 180000,
                'category_id' => 2,
                'image_url' => 'https://images.unsplash.com/photo-1486427944299-d1955d23e34d?w=500',
                'stock_quantity' => 25,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Cupcake Red Velvet',
                'description' => 'Cupcake red velvet với cream cheese frosting',
                'price' => 45000,
                'category_id' => 2,
                'image_url' => 'https://images.unsplash.com/photo-1603532648955-039310d9ed75?w=500',
                'stock_quantity' => 30,
                'is_featured' => false,
                'is_active' => true,
            ],

            // Cookies
            [
                'name' => 'Chocolate Chip Cookies',
                'description' => 'Bánh quy chocolate chip giòn tan, hộp 12 chiếc',
                'price' => 120000,
                'category_id' => 3,
                'image_url' => 'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?w=500',
                'stock_quantity' => 40,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Sugar Cookies',
                'description' => 'Bánh quy bơ truyền thống với đường cát',
                'price' => 100000,
                'category_id' => 3,
                'image_url' => 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?w=500',
                'stock_quantity' => 35,
                'is_featured' => false,
                'is_active' => true,
            ],

            // Donuts
            [
                'name' => 'Glazed Donuts',
                'description' => 'Bánh donut tráng men đường, hộp 6 chiếc',
                'price' => 150000,
                'category_id' => 4,
                'image_url' => 'https://images.unsplash.com/photo-1551024506-0bccd828d307?w=500',
                'stock_quantity' => 20,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Chocolate Donuts',
                'description' => 'Bánh donut chocolate với topping đa dạng',
                'price' => 170000,
                'category_id' => 4,
                'image_url' => 'https://images.unsplash.com/photo-1527515862127-a4fc05baf7a5?w=500',
                'stock_quantity' => 18,
                'is_featured' => true,
                'is_active' => true,
            ],

            // Macarons
            [
                'name' => 'French Macarons Mix',
                'description' => 'Hộp 12 macaron Pháp đủ vị: vanilla, chocolate, strawberry, pistachio',
                'price' => 250000,
                'category_id' => 5,
                'image_url' => 'https://images.unsplash.com/photo-1569864358642-9d1684040f43?w=500',
                'stock_quantity' => 15,
                'is_featured' => true,
                'is_active' => true,
            ],

            // Tarts
            [
                'name' => 'Fruit Tart',
                'description' => 'Bánh tart trái cây tươi với custard cream',
                'price' => 280000,
                'category_id' => 6,
                'image_url' => 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=500',
                'stock_quantity' => 10,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Lemon Tart',
                'description' => 'Bánh tart chanh với meringue và vỏ bánh giòn',
                'price' => 220000,
                'category_id' => 6,
                'image_url' => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=500',
                'stock_quantity' => 12,
                'is_featured' => false,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
