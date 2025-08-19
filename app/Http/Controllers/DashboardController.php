<?php

namespace App\Http\Controllers;

use App\Models\Item; // Make sure to import the Item model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Make sure to import Auth facade

class DashboardController extends Controller
{
    public function myBids()
    {
        $user = Auth::user();
        // Assuming bids are associated with the user and item
        $bids = $user->bids()->with('item')->latest()->get(); // Get bids made by the user, eager load item details

        // Get unique items the user has bid on
        $itemsBidOn = $bids->unique('item_id')->pluck('item');

        // For each item, find the user's highest bid and check if they are winning
        foreach ($itemsBidOn as $item) {
            $userHighestBid = $bids->where('item_id', $item->item_id)->max('bid_amount');
            $item->user_highest_bid = $userHighestBid;
            $item->is_winning = ($item->current_bidder_id == $user->user_id);
        }

        return view('dashboard.my_bids', ['myBids' => $bids]);
    }

    public function myListings()
    {
        $user = Auth::user();
        // Fetch items listed by the authenticated user
        $myListings = Item::where('seller_id', $user->user_id)
            ->orderBy('created_at', 'desc') // Order by most recent listing
            ->get();

        return view('dashboard.my_listings', ['myListedItems' => $myListings]);
    }

    public function myWinnings()
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    
        // Fetch all items where the authenticated user is the winner with pagination (5 per page)
        $myWinnings = Item::where('winner_id', Auth::id())
                          ->orderBy('auction_end_time', 'desc')
                          ->paginate(2); // change 5 to however many items per page you want
    
        return view('dashboard.my_winnings', [
            'winnings' => $myWinnings,
            'heading' => 'My Winnings'
        ]);
    }
}