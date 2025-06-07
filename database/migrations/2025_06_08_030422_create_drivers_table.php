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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('driver_code', 20)->unique(); // Mã tài xế (DR001, DR002...)
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 20);
            $table->text('address');
            $table->string('license_number', 50); // Số bằng lái xe
            $table->date('license_expiry'); // Ngày hết hạn bằng lái
            $table->string('vehicle_type', 50); // Loại xe (xe máy, xe tải nhỏ...)
            $table->string('vehicle_number', 20); // Biển số xe
            $table->enum('status', ['active', 'inactive', 'busy'])->default('active');
            $table->decimal('rating', 3, 2)->default(0); // Đánh giá từ 0-5
            $table->integer('total_deliveries')->default(0); // Tổng số đơn đã giao
            $table->text('notes')->nullable(); // Ghi chú
            $table->timestamp('last_active_at')->nullable(); // Lần hoạt động cuối
            $table->timestamps();

            // Indexes
            $table->index('driver_code');
            $table->index('status');
            $table->index('phone');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
