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
            $table->enum('payment_method', [
                'cod',           // Thanh toán khi nhận hàng
                'bank_transfer', // Chuyển khoản ngân hàng
                'credit_card',   // Thẻ tín dụng
                'momo',          // Ví MoMo
                'VNpay'        // VNPay
            ])->default('cod')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
    }
};
