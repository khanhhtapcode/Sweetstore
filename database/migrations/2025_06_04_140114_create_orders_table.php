<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('order_number', 50)->unique();
            $table->string('customer_name', 255);
            $table->string('customer_email', 255);
            $table->string('customer_phone', 20);
            $table->text('customer_address');
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', [
                'pending',
                'confirmed',
                'preparing',
                'ready',
                'delivered',
                'cancelled'
            ])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('status');
            $table->index('order_number');
            $table->index('customer_email');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
