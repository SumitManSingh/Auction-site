<x-auth-layout>
    <x-slot:heading>
        Dashboard
    </x-slot:heading>

    <div class="py-6">
        {{-- ONLY ATTEMPT TO ACCESS AUTH::USER() IF USER IS LOGGED IN --}}
        @auth
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                Welcome, {{ Auth::user()->username }}!
            </h2>

            {{-- Rest of your dashboard content --}}
            <p class="text-gray-700 mb-6">
                This is your personal dashboard. Here you can manage your activities on the auction site.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Card for all users: Browse Items --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Browse Items</h3>
                    <p class="text-gray-600 mb-4">
                        See all the active auction items available for bidding.
                    </p>
                    <a href="{{ route('items.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        View All Items
                    </a>
                </div>

                @if(Auth::user()->role === 'seller' || Auth::user()->role === 'admin')
                    {{-- Card for Sellers/Admins: List a New Item --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">List a New Item</h3>
                        <p class="text-gray-600 mb-4">
                            Start a new auction by listing an item for sale.
                        </p>
                        <a href="{{ route('items.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            List New Item
                        </a>
                    </div>

                    {{-- Card for Sellers/Admins: Manage My Listings (Placeholder) --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Manage My Listings</h3>
                        <p class="text-gray-600 mb-4">
                            View and manage the items you have listed for auction.
                        </p>
                        <a href="{{ route('dashboard.my_listings') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            View My Listings
                        </a>
                    </div>
                @endif

                @if(Auth::user()->role === 'bidder' || Auth::user()->role === 'user')
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">My Bids</h3>
                        <p class="text-gray-600 mb-4">
                            Track your active bids and review your bidding history.
                        </p>
                        <a href="{{ route('dashboard.my_bids') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            View My Bids
                        </a>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">My Winnings</h3>
                        <p class="text-gray-600 mb-4">
                            View the items you have successfully won at auction.
                        </p>
                        <a href="{{ route('dashboard.my_winnings') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-400">
                            View My Winnings
                        </a>
                    </div>
                @endif

                @if(Auth::user()->role === 'admin')
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Admin Panel</h3>
                        <p class="text-gray-600 mb-4">
                            Access administrative tools and site management.
                        </p>
                        <a href="#"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Go to Admin Tools
                        </a>
                    </div>
                @endif
            </div>
        @else
            {{-- Content for guests on the dashboard page if they somehow reach it --}}
            <p class="text-gray-700">Please log in to view your dashboard.</p>
            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Log In Here</a>
        @endauth
    </div>
</x-auth-layout>