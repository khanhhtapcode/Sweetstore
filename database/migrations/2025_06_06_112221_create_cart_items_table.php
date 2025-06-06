<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Liên kết với người dùng (nullable nếu chưa đăng nhập)
            $table->string('session_id')->nullable(); // Lưu session ID cho khách chưa đăng nhập
            $table->unsignedBigInteger('product_id'); // ID sản phẩm
            $table->integer('quantity')->default(1); // Số lượng
            $table->decimal('price', 10, 2); // Giá sản phẩm tại thời điểm thêm vào giỏ
            $table->string('image_url')->nullable(); // Ảnh sản phẩm (tùy chọn)
            $table->string('product_name'); // Tên sản phẩm (để hiển thị)
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
