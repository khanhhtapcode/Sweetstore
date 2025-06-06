<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    public function up()
    {
        Schema::create('rating_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rating_id')->constrained('ratings')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('reply');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('rating_replies');
    }
};
