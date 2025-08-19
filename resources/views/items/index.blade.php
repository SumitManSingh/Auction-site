<x-layout>
    <x-slot:heading>
        {{ $heading }}
    </x-slot:heading>

    <form action="{{ route('items.index') }}" method="GET" class="mb-6">
        <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-2">
            <!-- Search Input -->
            <input type="text" name="search" placeholder="Search for items..."
                       value="{{ request('search') }}"
                       class="flex-1 block w-full px-4 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            
            <!-- Category Filter -->
            <select name="category" class="w-full sm:w-auto block px-4 py-2 text-gray-900 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">All Categories</option>
                {{-- Dynamically populate categories from the controller --}}
                @foreach ($categories as $category)
                    <option value="{{ $category->category_name }}" @selected(request('category') == $category->category_name)>{{ $category->category_name }}</option>
                @endforeach
            </select>
            
            <!-- Price Range Filter -->
            <select name="price_range" class="w-full sm:w-auto block px-4 py-2 text-gray-900 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">Any Price</option>
                <option value="0-50" @selected(request('price_range') == '0-50')>$0 - $50</option>
                <option value="50-200" @selected(request('price_range') == '50-200')>$50 - $200</option>
                <option value="200-500" @selected(request('price_range') == '200-500')>$200 - $500</option>
                <option value="500+" @selected(request('price_range') == '500+')>$500+</option>
            </select>

            <button type="submit"
                        class="w-full sm:w-auto bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 font-medium transition duration-200">
                Filter
            </button>
        </div>
    </form>
    
    @if(request('search') || request('category') || request('price_range'))
        <h2 class="text-xl font-semibold text-gray-800 mb-6">
            Showing results for: 
            @if(request('search')) "{{ request('search') }}"@endif
            @if(request('category')) in {{ request('category') }}@endif
            @if(request('price_range')) with price range {{ request('price_range') }}@endif
        </h2>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($items as $item)
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <!-- Image Container -->
                <a href="{{ route('items.show', $item->item_id) }}">
                    @if ($item->image_url)
                        <img class="h-48 w-full object-cover" src="{{ Storage::url($item->image_url) }}" alt="{{ $item->item_name }}">
                    @else
                        <div class="h-48 w-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-sm">No Image</div>
                    @endif
                </a>
                
                <!-- Details Container -->
                <div class="px-4 py-5 sm:p-6">
                    <a href="{{ route('items.show', $item->item_id) }}" class="block">
                        <p class="text-lg font-medium text-indigo-600 hover:text-indigo-900">{{ $item->item_name }}</p>
                        <p class="mt-2 text-sm text-gray-900">Current Bid: <span class="font-semibold">${{ number_format($item->current_bid, 2) }}</span></p>
                        <p class="mt-1 text-xs text-gray-500">Ends: {{ $item->auction_end_time->format('M d, Y H:i A') }}</p>
                        <p class="mt-1 text-xs text-gray-500">Seller: {{ $item->seller->username }}</p>
                    </a>
                </div>
                
                <div class="px-4 py-4 sm:px-6 bg-gray-50 text-right">
                    <a href="{{ route('items.show', $item->item_id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View Details &rarr;</a>
                </div>
            </div>
        @empty
            <p class="text-gray-600 col-span-full text-center">No active auction items found at the moment.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $items->links() }}
    </div>
</x-layout>
