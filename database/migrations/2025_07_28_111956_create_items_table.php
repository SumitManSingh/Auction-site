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
        Schema::create('items', function (Blueprint $table) {
            $table->id('item_id'); // Primary key for items, explicitly named 'item_id'

            // Item Details
            $table->string('item_name');
            $table->text('description');
            $table->decimal('starting_bid', 10, 2); // Initial bid amount
            $table->string('condition')->nullable(); // <-- ADD THIS LINE for the 'condition' column!

            // Auction Status & Bidding
            $table->decimal('current_bid', 10, 2)->default(0.00); // Current highest bid
            $table->foreignId('current_bidder_id') // ID of the user with the current highest bid
                  ->nullable()
                  ->constrained('users', 'user_id') // Correctly referencing 'user_id' in 'users' table
                  ->onDelete('set null'); // If the bidder user is deleted, set to null

            $table->dateTime('auction_end_time'); // When the auction ends
            $table->string('status')->default('active'); // e.g., 'active', 'ended', 'sold', 'cancelled'
            $table->decimal('min_bid_increment', 8, 2)->default(1.00); // Minimum amount a bid must increase by

            // Relationships
            $table->foreignId('seller_id') // ID of the user who listed the item
                  ->constrained('users', 'user_id') // Correctly referencing 'user_id' in 'users' table
                  ->onDelete('cascade'); // If seller is deleted, their items are deleted

            $table->foreignId('category_id') // ID of the category this item belongs to
                  ->constrained('categories', 'category_id') // Correctly referencing 'category_id' in 'categories' table
                  ->onDelete('restrict'); // Don't delete category if items are linked to it

            // Media
            $table->string('image_url')->nullable(); // Path to the item's main image

            // Timestamps for creation and last update
            $table->timestamps(); // Adds 'created_at' and 'updated_at' columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};