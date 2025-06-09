<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('driver_ratings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('ID người dùng thực hiện đánh giá');

            $table->foreignId('driver_id')
                ->constrained('drivers')
                ->onDelete('cascade')
                ->comment('ID tài xế được đánh giá');

            $table->foreignId('order_id')
                ->constrained('orders')
                ->onDelete('cascade')
                ->comment('ID đơn hàng liên quan');

            $table->unsignedTinyInteger('rating')
                ->default(0)
                ->comment('Số sao đánh giá tài xế, từ 1 đến 5');

            $table->text('comment')->nullable()
                ->comment('Nhận xét chi tiết của người dùng');

            $table->timestamp('rated_at')->nullable()
                ->comment('Thời gian người dùng thực hiện đánh giá');

            $table->timestamps();

            // Đảm bảo một người chỉ đánh giá một đơn hàng một lần
            $table->unique(['user_id', 'order_id'], 'unique_user_order_rating');

            // Tối ưu truy vấn
            $table->index('driver_id'); // Chỉ mục đơn cho driver_id
            $table->index('rated_at');  // Chỉ mục cho thời gian đánh giá
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_ratings');
    }
};