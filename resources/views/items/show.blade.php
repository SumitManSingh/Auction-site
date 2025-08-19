<x-layout>
    <x-slot:heading>
        {{ $item->item_name }}
    </x-slot:heading>

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <div class="md:flex md:space-x-8">
            {{-- Item Image --}}
            <div class="md:w-1/2">
                <img src="{{ $item->image_url ? asset('storage/' . $item->image_url) : 'https://via.placeholder.com/400x300?text=No+Image' }}"
                    alt="{{ $item->item_name }}" class="w-full h-auto object-cover rounded-lg">

            </div>

            {{-- Item Details --}}
            <div class="md:w-1/2 mt-6 md:mt-0">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $item->item_name }}</h1>
                <p class="text-gray-700 mb-4">{{ $item->description }}</p>

                <div class="mb-4">
                    <p class="text-lg text-gray-800">
                        <strong>Starting Bid:</strong> ${{ number_format($item->starting_bid, 2) }}
                    </p>
                    <p class="text-lg text-gray-800">
                        <strong>Minimum Bid Increment:</strong> ${{ number_format($item->min_bid_increment, 2) }}
                    </p>

                    @if ($item->winner_id)
                        <p class="text-xl font-semibold text-green-600">
                            <strong>Sold for:</strong> ${{ number_format($item->final_bid, 2) }}
                        </p>
                        <p class="text-sm text-gray-600">
                            (Winner: {{ $item->winner->username ?? 'N/A' }})
                        </p>
                    @else
                        <p class="text-xl font-semibold text-gray-900">
                            <strong>Current Bid:</strong> ${{ number_format($item->current_bid ?? $item->starting_bid, 2) }}
                        </p>
                        @if ($item->current_bidder_id)
                            <p class="text-sm text-gray-600">
                                (Highest bid by: {{ $item->currentBidder->username ?? 'N/A' }})
                            </p>
                        @endif
                    @endif
                </div>

                {{-- Auction Status --}}
                <div class="mb-6">
                    <p class="text-md text-gray-700">
                        <strong>Auction Status:</strong>
                        <span class="font-medium">
                            @if ($item->auction_end_time > now())
                                <span class="text-indigo-600">Active (ends in
                                    {{ $item->auction_end_time->diffForHumans(null, true, true, 2) }})</span>
                            @elseif ($item->status === 'closed')
                                <span class="text-red-500 font-bold">Closed - No Bids</span>
                            @elseif ($item->status === 'sold')
                                <span class="text-green-600 font-bold">Sold</span>
                            @else
                                <span class="text-red-500 font-bold">Auction Closed</span>
                            @endif
                        </span>
                    </p>
                    @if ($item->auction_end_time > now())
                        <p class="text-sm text-gray-500">
                            (Ends at: {{ $item->auction_end_time->format('M d, Y H:i A') }})
                        </p>
                    @endif
                </div>

                {{-- Bidding Section --}}
                @if ($item->auction_end_time > now())
                    @auth
                        @if(Auth::id() !== $item->seller_id)
                            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-6">
                                <h3 class="text-xl font-semibold text-gray-800 mb-4">Place Your Bid</h3>
                                @if ($errors->any())
                                    <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form method="POST" action="{{ route('bids.store', $item->item_id) }}">
                                    @csrf
                                    <label for="bid_amount" class="block text-sm font-medium text-gray-700 mb-2">Your Bid
                                        ($):</label>
                                    @php
                                        $nextMinBid = ($item->current_bid ?? $item->starting_bid) + $item->min_bid_increment;
                                    @endphp
                                    <input type="number" id="bid_amount" name="bid_amount" step="0.01"
                                        min="{{ number_format($nextMinBid, 2, '.', '') }}"
                                        value="{{ old('bid_amount', number_format($nextMinBid, 2, '.', '')) }}" required
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 mb-4">
                                    <button type="submit"
                                        class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        Place Bid
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 text-blue-800 mb-6">
                                You are the seller of this item.
                            </div>
                        @endif
                    @else
                        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200 text-yellow-800 mb-6">
                            Please <a href="{{ route('login') }}" class="font-medium text-yellow-700 hover:underline">log in</a>
                            to place a bid.
                        </div>
                    @endauth
                @endif

                {{-- Bid History --}}
                <div class="mt-8 bg-white p-6 rounded-lg shadow-md border border-gray-200">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-4">Bid History</h3>
                    @if ($item->bids->isEmpty())
                        <p class="text-gray-600">No bids have been placed on this item yet.</p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach ($item->bids as $bid)
                                <li class="py-3 flex justify-between items-center">
                                    <div>
                                        <p class="text-lg font-medium text-gray-900">
                                            ${{ number_format($bid->bid_amount, 2) }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            by {{ $bid->bidder->username ?? 'Unknown Bidder' }}
                                        </p>
                                    </div>
                                    <span class="text-sm text-gray-500">
                                        {{ $bid->bid_timestamp->format('M d, Y H:i A') }}
                                        ({{ $bid->bid_timestamp->diffForHumans() }})
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                {{-- FEEDBACK SECTION --}}
                <h3 class="text-2xl font-semibold mt-8">Feedback</h3>

                {{-- Display all feedback --}}
                @foreach($item->feedback as $feedback)
                    <div class="border p-2 mb-2 rounded">
                        <strong>{{ $feedback->fromUser->username ?? 'Anonymous' }}</strong>
                        <span>⭐ {{ $feedback->rating }}/5</span>
                        <p>{{ $feedback->comment }}</p>
                    </div>
                @endforeach


                {{-- Feedback form for eligible users --}}
                @auth
                    @php
                        $userId = Auth::id();
                        $canFeedback = false;
                        $recipient = null;

                        // Seller can leave feedback for winner
                        if ($userId === $item->seller_id) {
                            $winner = $item->bids()->orderByDesc('bid_amount')->first()?->bidder_id;
                            if ($winner) {
                                $recipient = $winner;
                                $existing = \App\Models\Feedback::where('item_id', $item->item_id)
                                    ->where('from_user_id', $userId)
                                    ->first();
                                $canFeedback = !$existing;
                            }
                        } else {
                            // Buyer can leave feedback for seller
                            $hasBid = $item->bids()->where('bidder_id', $userId)->exists();
                            if ($hasBid) {
                                $recipient = $item->seller_id;
                                $existing = \App\Models\Feedback::where('item_id', $item->item_id)
                                    ->where('from_user_id', $userId)
                                    ->first();
                                $canFeedback = !$existing;
                            }
                        }
                    @endphp

                    @if($canFeedback)
                        <div class="mt-6 p-4 border rounded bg-gray-50">
                            <h4 class="text-xl font-semibold mb-2">Leave Feedback</h4>
                            <form action="{{ route('feedback.store', $item->item_id) }}" method="POST">
                                @csrf
                                <label for="rating">Rating:</label>
                                <select name="rating" id="rating" required class="mb-2 p-1 border rounded">
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>

                                <label for="comment">Comment:</label>
                                <textarea name="comment" id="comment" rows="3" maxlength="500"
                                    class="mb-2 p-1 border rounded w-full"></textarea>

                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                    Submit Feedback
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth

            </div>
        </div>
    </div>

    {{-- Back to all items --}}
    <div class="mt-8 text-center">
        <a href="{{ route('items.index') }}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            &larr; Back to All Items
        </a>
    </div>

</x-layout>