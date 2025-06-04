<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('image_url', 500)->nullable();
            $table->integer('stock_quantity')->unsigned()->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes
            $table->index('category_id');
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('price');
            $table->index('stock_quantity');
            $table->index(['is_active', 'is_featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
