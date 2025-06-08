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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('driver_id')->nullable()->after('user_id')
                ->constrained('drivers')->onDelete('set null');
            $table->timestamp('assigned_at')->nullable()->after('driver_id'); // Thời gian gán tài xế
            $table->timestamp('picked_up_at')->nullable()->after('assigned_at'); // Thời gian lấy hàng
            $table->timestamp('delivered_at')->nullable()->after('picked_up_at'); // Thời gian giao hàng
            $table->text('delivery_notes')->nullable()->after('delivered_at'); // Ghi chú giao hàng

            // Thêm status mới cho giao hàng
            $table->dropColumn('status');
        });

        // Tạo lại cột status với các giá trị mới
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'confirmed',
                'preparing',
                'ready',
                'assigned', // Đã gán tài xế
                'picked_up', // Tài xế đã lấy hàng
                'delivering', // Đang giao hàng
                'delivered',
                'cancelled'
            ])->default('pending')->after('total_amount');

            // Index cho tối ưu query
            $table->index('driver_id');
            $table->index(['driver_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropColumn([
                'driver_id',
                'assigned_at',
                'picked_up_at',
                'delivered_at',
                'delivery_notes'
            ]);

            // Khôi phục status cũ
            $table->dropColumn('status');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'confirmed',
                'preparing',
                'ready',
                'delivered',
                'cancelled'
            ])->default('pending')->after('total_amount');
        });
    }
};
