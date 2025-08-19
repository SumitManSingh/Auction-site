<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Item;
use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class FeedbackController extends Controller
{
    /**
     * Store feedback for an item transaction.
     */
    public function store(Request $request, Item $item)
    {
        $userId = Auth::id();

        // Check if the auction has ended and has a winner
        if (!$item->winner_id) {
            throw ValidationException::withMessages([
                'feedback' => ['Feedback can only be given for completed auctions with a winner.']
            ]);
        }

        // Determine feedback direction
        if ($userId === $item->winner_id) {
            // Buyer giving feedback to seller
            $toUserId = $item->seller_id;
        } elseif ($userId === $item->seller_id) {
            // Seller giving feedback to buyer
            $toUserId = $item->winner_id;
        } else {
            // Others cannot leave feedback
            throw ValidationException::withMessages([
                'feedback' => ['You are not allowed to give feedback for this item.']
            ]);
        }

        // Prevent duplicate feedback
        $existing = Feedback::where('item_id', $item->item_id)
            ->where('from_user_id', $userId)
            ->first();

        if ($existing) {
            throw ValidationException::withMessages([
                'feedback' => ['You have already submitted feedback for this transaction.']
            ]);
        }

        // Validate input
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // Store feedback
        Feedback::create([
            'item_id' => $item->item_id,
            'from_user_id' => $userId,
            'to_user_id' => $toUserId,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('items.show', $item)
            ->with('success', 'Feedback submitted successfully!');
    }
}
