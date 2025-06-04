<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity')->unsigned();
            $table->decimal('price', 10, 2); // Giá tại thời điểm đặt hàng
            $table->timestamps();

            // Indexes
            $table->index('order_id');
            $table->index('product_id');
            $table->unique(['order_id', 'product_id']); // Một sản phẩm chỉ xuất hiện 1 lần trong 1 đơn hàng
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
