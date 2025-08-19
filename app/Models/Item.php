<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $primaryKey = 'item_id';
    protected $table = 'items';

    protected $fillable = [
        'item_name',
        'description',
        'starting_bid',
        'current_bid',
        'min_bid_increment',
        'auction_end_time',
        'condition',
        'image_url',
        'seller_id',
        'category_id',
        'status',
        'current_bidder_id',
        'winner_id', // <-- ADD THIS LINE
        'final_bid', // <-- ADD THIS LINE
    ];

    protected $casts = [
        'auction_end_time' => 'datetime',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id', 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class, 'item_id', 'item_id');
    }

    public function currentBidder()
    {
        return $this->belongsTo(User::class, 'current_bidder_id', 'user_id');
    }

    public function winner() // <-- ADD THIS RELATIONSHIP
    {
        return $this->belongsTo(User::class, 'winner_id', 'user_id');
    }

    public function feedback()
    {
         // Load feedback along with the user who gave it
    return $this->hasMany(\App\Models\Feedback::class, 'item_id')
    ->with('fromUser');
    }
}