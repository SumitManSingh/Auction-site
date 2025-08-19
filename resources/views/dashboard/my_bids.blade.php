<x-auth-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Bids') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @forelse ($myBids as $bid)
                        <div class="mb-4 p-4 border border-gray-200 dark:border-gray-700 rounded-md">
                            <h3 class="text-lg font-bold">
                                Bid on: <a href="{{ route('items.show', $bid->item) }}" class="text-blue-500 hover:underline">{{ $bid->item->item_name }}</a>
                            </h3>
                            <p class="text-sm">
                                Current Bid: ${{ number_format($bid->bid_amount, 2) }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Placed at: {{ $bid->created_at->format('M d, Y H:i A') }}
                            </p>
                        </div>
                    @empty
                        <p>You haven't placed any bids yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-auth-layout>