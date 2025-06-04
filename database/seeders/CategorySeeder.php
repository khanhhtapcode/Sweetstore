<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Bánh Kem',
                'description' => 'Các loại bánh kem tươi ngon, đa dạng hương vị',
                'image_url' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=400',
                'is_active' => true,
            ],
            [
                'name' => 'Bánh Cupcake',
                'description' => 'Bánh cupcake nhỏ xinh, đủ màu sắc',
                'image_url' => 'https://images.unsplash.com/photo-1486427944299-d1955d23e34d?w=400',
                'is_active' => true,
            ],
            [
                'name' => 'Bánh Cookie',
                'description' => 'Bánh quy giòn tan, thơm ngon',
                'image_url' => 'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?w=400',
                'is_active' => true,
            ],
            [
                'name' => 'Bánh Donut',
                'description' => 'Bánh donut mềm mịn với nhiều topping',
                'image_url' => 'https://images.unsplash.com/photo-1551024506-0bccd828d307?w=400',
                'is_active' => true,
            ],
            [
                'name' => 'Bánh Macaron',
                'description' => 'Bánh macaron Pháp cao cấp',
                'image_url' => 'https://images.unsplash.com/photo-1569864358642-9d1684040f43?w=400',
                'is_active' => true,
            ],
            [
                'name' => 'Bánh Tart',
                'description' => 'Bánh tart trái cây tươi mát',
                'image_url' => 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?w=400',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
