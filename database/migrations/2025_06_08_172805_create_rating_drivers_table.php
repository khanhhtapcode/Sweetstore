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
        Schema::create('rating_drivers', function (Blueprint $table) {
            $table->id(); // id: bigint(20) unsigned, khóa chính, sử dụng $table->id() thay vì $table->bigIncrements()

            $table->unsignedBigInteger('driver_id'); // FK -> sweetstore_drivers(id)
            $table->unsignedBigInteger('order_id');  // FK -> sweetstore_orders(id)

            $table->decimal('rating', 2, 1);          // ví dụ: 4.5
            $table->text('comment')->nullable();      // nhận xét
            $table->json('criteria')->nullable();     // các tiêu chí dạng JSON

            $table->timestamps(); // created_at, updated_at

            // Foreign key constraints
            $table->foreign('driver_id')
                  ->references('id')
                  ->on('sweetstore_drivers')
                  ->onDelete('cascade');

            $table->foreign('order_id')
                  ->references('id')
                  ->on('sweetstore_orders')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rating_drivers');
    }
};