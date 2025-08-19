<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $primaryKey = 'bid_id';
    protected $table = 'bids';

    protected $fillable = [
        'item_id',
        'bidder_id',
        'bid_amount',
        'bid_timestamp',
    ];

    protected $casts = [
        'bid_timestamp' => 'datetime',
    ];

    /**
     * A bid belongs to a specific item.
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    /**
     * A bid belongs to a user (bidder).
     */
    public function bidder()
    {
        return $this->belongsTo(User::class, 'bidder_id');
    }
}
