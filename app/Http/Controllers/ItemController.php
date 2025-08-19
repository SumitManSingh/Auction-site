<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ItemController extends Controller
{
    /**
     * Display a listing of all items with optional search functionality.
     */
    public function index(Request $request)
    {
        $query = Item::with(['seller', 'category'])->where('auction_end_time', '>', now());

        // Search by item name
        if ($search = $request->input('search')) {
            $query->where('item_name', 'like', '%' . $search . '%');
        }

        // Filter by category
        if ($category = $request->input('category')) {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('category_name', $category);
            });
        }

        // Filter by price range
        if ($priceRange = $request->input('price_range')) {
            switch ($priceRange) {
                case '0-50':
                    $query->whereBetween('current_bid', [0, 50]);
                    break;
                case '50-200':
                    $query->whereBetween('current_bid', [50, 200]);
                    break;
                case '200-500':
                    $query->whereBetween('current_bid', [200, 500]);
                    break;
                case '500+':
                    $query->where('current_bid', '>=', 500);
                    break;
            }
        }

        // Get all categories for the filter dropdown
        $categories = Category::all();

        // Get the paginated results
        $items = $query->latest()->paginate(10);

        return view('items.index', [
            'items' => $items,
            'categories' => $categories, // <-- PASS THE CATEGORIES TO THE VIEW
            'heading' => 'All Auction Items'
        ]);
    }

    /**
     * Show the form to create a new item.
     * Only accessible by seller or admin.
     */
    public function create()
    {
        if (Auth::user()->role !== 'seller' && Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'You are not authorized to list items.');
        }

        $categories = Category::all();

        return view('items.create', [
            'categories' => $categories,
            'heading' => 'List a New Item'
        ]);
    }

    /**
     * Store a new item in the database.
     * Handles image upload and validation.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'seller' && Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'You are not authorized to list items.');
        }

        // Validate form inputs
        $validator = Validator::make($request->all(), [
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'starting_bid' => 'required|numeric|min:0.01',
            'min_bid_increment' => 'required|numeric|min:0.01',
            'auction_end_time' => 'required|date|after:now',
            'condition' => 'required|string|in:New,Used,Refurbished',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,category_id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->except(['_token', 'image']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('item_images', 'public');
            $data['image_url'] = $imagePath;
        } else {
            $data['image_url'] = null;
        }

        // Set additional item data
        $data['seller_id'] = Auth::id();
        $data['status'] = 'active';
        $data['current_bid'] = $data['starting_bid'];

        Item::create($data);

        return redirect()->route('items.index')->with('success', 'Item listed successfully!');
    }

    /**
     * Display a single item with all related information.
     */
    public function show(Item $item)
    {
        // Load relationships to avoid N+1 queries
        $item->load('seller', 'category', 'bids.bidder', 'currentBidder', 'winner');

        // Check if auction ended and no winner is set
        if ($item->auction_end_time <= now() && is_null($item->winner_id)) {
            $highestBid = Bid::where('item_id', $item->item_id)
                ->orderBy('bid_amount', 'desc')
                ->first();

            if ($highestBid) {
                // Auction has bids: set winner and final bid
                $item->winner_id = $highestBid->bidder_id;
                $item->final_bid = $highestBid->bid_amount;
                $item->status = 'sold';
            } else {
                // No bids: auction closed without sale
                $item->final_bid = 0;
                $item->status = 'closed';
            }

            $item->save();
        }

        // Sort bids by most recent first
        $item->setRelation('bids', $item->bids->sortByDesc('bid_timestamp'));

        return view('items.show', [
            'item' => $item,
            'heading' => $item->item_name
        ]);
    }

    /**
     * Show form to edit an existing item.
     * Only accessible by the seller of the item or admin.
     */
    public function edit(Item $item)
    {
        if (Auth::id() !== $item->seller_id && Auth::user()->role !== 'admin') {
            return redirect()->route('items.show', $item)->with('error', 'You are not authorized to edit this item.');
        }

        // Prevent editing if bids exist or item is not active
        if ($item->bids()->count() > 0 || $item->status !== 'active') {
            return redirect()->route('items.show', $item)->with('error', 'This item cannot be edited as it has bids or is no longer active.');
        }

        $categories = Category::all();

        return view('items.edit', [
            'item' => $item,
            'categories' => $categories,
            'heading' => 'Edit Item: ' . $item->item_name
        ]);
    }

    /**
     * Update an existing item in the database.
     * Handles image replacement and validation.
     */
    public function update(Request $request, Item $item)
    {
        if (Auth::id() !== $item->seller_id && Auth::user()->role !== 'admin') {
            return redirect()->route('items.show', $item)->with('error', 'You are not authorized to update this item.');
        }

        if ($item->bids()->count() > 0 || $item->status !== 'active') {
            return redirect()->route('items.show', $item)->with('error', 'This item cannot be updated as it has bids or is no longer active.');
        }

        // Validate form inputs
        $validator = Validator::make($request->all(), [
            'item_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'starting_bid' => 'required|numeric|min:0.01',
            'min_bid_increment' => 'required|numeric|min:0.01',
            'auction_end_time' => 'required|date|after_or_equal:' . Carbon::now()->format('Y-m-d H:i:s'),
            'condition' => 'required|string|in:New,Used,Refurbished',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,category_id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->except(['_token', '_method', 'image']);

        // Replace image if a new file is uploaded
        if ($request->hasFile('image')) {
            if ($item->image_url) {
                Storage::disk('public')->delete($item->image_url);
            }
            $imagePath = $request->file('image')->store('item_images', 'public');
            $data['image_url'] = $imagePath;
        }

        $item->update($data);

        return redirect()->route('items.show', $item)->with('success', 'Item updated successfully!');
    }

    /**
     * Delete an item from the database.
     * Only accessible by the seller of the item or admin.
     */
    public function destroy(Item $item)
    {
        if (Auth::id() !== $item->seller_id && Auth::user()->role !== 'admin') {
            return redirect()->route('items.show', $item)->with('error', 'You are not authorized to delete this item.');
        }

        // Prevent deletion if bids exist
        if ($item->bids()->count() > 0) {
            return redirect()->route('items.show', $item)->with('error', 'Cannot delete item with existing bids.');
        }

        // Delete associated image
        if ($item->image_url) {
            Storage::disk('public')->delete($item->image_url);
        }

        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
