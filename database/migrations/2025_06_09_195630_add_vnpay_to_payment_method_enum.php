<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sử dụng raw SQL để modify ENUM
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('cod','bank_transfer','credit_card','momo','zalopay','vnpay') DEFAULT 'cod'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Trở về ENUM ban đầu
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_method ENUM('cod','bank_transfer','credit_card','momo','zalopay') DEFAULT 'cod'");
    }
};
