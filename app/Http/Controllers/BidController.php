<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class BidController extends Controller
{
    /**
     * Store a newly created bid in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Item $item): RedirectResponse
    {
        // 1. Ensure user is authenticated
        if (!Auth::check()) {
            return back()->with('error', 'You must be logged in to place a bid.');
        }

        // 2. Check if the auction has ended
        if ($item->auction_end_time && now()->greaterThanOrEqualTo($item->auction_end_time)) {
            return back()->with('error', 'This auction has ended.');
        }

        // 3. Ensure the bidder is not the seller of the item
        if ($item->seller_id === Auth::id()) {
            return back()->with('error', 'You cannot bid on your own item.');
        }

        // Determine the next minimum bid amount
        $nextMinBid = ($item->current_bid ?? $item->starting_bid) + $item->min_bid_increment;

        // 4. Validate the incoming bid request
        $validated = $request->validate([
            'bid_amount' => [
                'required',
                'numeric',
                'min:' . $nextMinBid,
            ],
        ]);

        // 5. Create the new bid
        $bid = new Bid();
        $bid->item_id = $item->item_id;
        $bid->bidder_id = Auth::id();
        $bid->bid_amount = $validated['bid_amount'];
        $bid->bid_timestamp = now();
        $bid->save();

        // 6. Update the item's current bid and current bidder
        $item->current_bid = $validated['bid_amount'];
        $item->current_bidder_id = Auth::id();
        $item->save();

        return back()->with('success', 'Your bid has been placed successfully!');
    }
}