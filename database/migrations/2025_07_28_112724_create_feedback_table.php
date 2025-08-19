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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id('feedback_id');
            $table->foreignId('item_id')->constrained('items', 'item_id');
            $table->foreignId('from_user_id')->constrained('users', 'user_id');
            $table->foreignId('to_user_id')->constrained('users', 'user_id');
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->timestamp('feedback_date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
