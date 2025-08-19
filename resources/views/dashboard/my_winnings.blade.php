<x-auth-layout>
    <x-slot:heading>
        My Winnings
    </x-slot:heading>

    <div class="py-6">
        @auth
            @if(Auth::user()->role === 'bidder' || Auth::user()->role === 'user')
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                    Congratulations, {{ Auth::user()->username }}!
                </h2>
                <p class="text-gray-700 mb-6">
                    Here are the items you have successfully won.
                </p>

                @if($winnings->isEmpty())
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                        <p class="text-gray-600">You haven't won any items yet. Keep bidding!</p>
                        <a href="{{ route('items.index') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Browse All Items
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($winnings as $item)
                            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <h3 class="text-lg font-bold text-gray-900">{{ $item->name }}</h3>
                                    <p class="text-gray-600 text-sm mt-1 truncate">{{ $item->description }}</p>
                                    <div class="mt-4 flex justify-between items-center">
                                        <div>
                                            <span class="text-gray-500 text-xs">Winning Bid:</span>
                                            <p class="text-lg font-semibold text-green-600">${{ number_format($item->final_bid, 2) }}</p>
                                        </div>
                                        <a href="{{ route('items.show', $item) }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm">
                                            View Item
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @else
                <p class="text-gray-700">You do not have permission to view this page.</p>
            @endif
        @else
            <p class="text-gray-700">Please log in to view your winnings.</p>
            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Log In Here</a>
        @endauth

        <!-- Pagination links -->
<div>
    {{ $winnings->links() }}
</div>
    </div>
</x-auth-layout>