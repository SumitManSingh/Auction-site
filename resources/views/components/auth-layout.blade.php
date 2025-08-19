<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://js.pusher.com/beams/2.1.0/push-notifications-cdn.js"></script>
    <script src="{{ asset('/js/service-worker.js') }}">   </script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'ui-sans-serif', 'system-ui'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-down': 'slideDown 0.3s ease-out',
                        'pulse-soft': 'pulseSoft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(-10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideDown: {
                            '0%': { opacity: '0', transform: 'translateY(-10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        pulseSoft: {
                            '0%, 100%': { opacity: '1' },
                            '50%': { opacity: '0.8' }
                        }
                    }
                }
            }
        }
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full font-sans antialiased">
    <div class="min-h-full">
        <!-- Enhanced Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <!-- Enhanced Logo -->
                            <div class="flex items-center space-x-3">
                                <div class="h-8 w-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <span class="text-xl font-bold text-gray-900">{{ config('app.name', 'Dashboard') }}</span>
                            </div>
                        </div>
                        <div class="hidden sm:ml-8 sm:block">
                            <div class="flex space-x-1">
                                <!-- Enhanced Navigation Links -->
                                <a href="{{ route('dashboard') }}" 
                                   class="{{ request()->routeIs('dashboard') ? 'bg-indigo-100 text-indigo-700 border-indigo-200' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50 border-transparent' }} px-4 py-2 text-sm font-medium rounded-lg border transition-all duration-200">
                                    Dashboard
                                </a>
                                <a href="{{ route('items.index') }}" 
                                   class="{{ request()->routeIs('items.index') ? 'bg-indigo-100 text-indigo-700 border-indigo-200' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50 border-transparent' }} px-4 py-2 text-sm font-medium rounded-lg border transition-all duration-200">
                                    All Items
                                </a>
                                <a href="{{ route('messages.inbox') }}" 
                                   class="{{ request()->routeIs('messages.inbox') ? 'bg-indigo-100 text-indigo-700 border-indigo-200' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50 border-transparent' }} px-4 py-2 text-sm font-medium rounded-lg border transition-all duration-200">
                                    Messages
                                    @php
        $unreadCount = \App\Models\Message::where('receiver_id', auth()->id())
                                          ->where('is_read', false)
                                          ->count();
    @endphp
    @if($unreadCount > 0)
        <span class="ml-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
            {{ $unreadCount }}
        </span>
    @endif
                                </a>
                                
                            </div>
                        </div>
                    </div>
                    
                    <!-- Enhanced User Menu -->
                    <div class="flex items-center space-x-4">
                        @guest
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 text-sm font-medium transition-colors duration-200">Log In</a>
                            <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-200">Register</a>
                        @else
                            <!-- Notification Bell (placeholder) -->
                            <button class="relative p-2 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>
                                <span class="absolute top-1 right-1 h-2 w-2 bg-red-500 rounded-full"></span>
                            </button>

                            <!-- Enhanced Profile Dropdown -->
                            <div class="relative" x-data="{ userMenuOpen: false }">
                                <button @click="userMenuOpen = !userMenuOpen" type="button" 
                                        class="flex items-center space-x-3 rounded-lg p-2 text-sm hover:bg-gray-50 transition-colors duration-200" 
                                        id="user-menu-button">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                        <span class="text-white text-sm font-semibold">{{ substr(Auth::user()->username, 0, 1) }}</span>
                                    </div>
                                    <div class="hidden sm:block text-left">
                                        <div class="font-medium text-gray-900">{{ Auth::user()->username }}</div>
                                       
                                    </div>
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>

                                <div x-show="userMenuOpen"
                                    @click.away="userMenuOpen = false"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-xl bg-white py-2 shadow-lg ring-1 ring-gray-200 focus:outline-none animate-slide-down">
                                    
                                    <div class="px-4 py-3 border-b border-gray-100">
                                        <div class="font-medium text-gray-900">{{ Auth::user()->name }}</div>
                                        <div class="text-sm text-gray-500">{{ Auth::user()->email ?? 'user@example.com' }}</div>
                                        <div class="text-xs text-indigo-600 bg-indigo-50 px-2 py-1 rounded-full inline-block mt-1">{{ ucfirst(Auth::user()->role) }}</div>
                                    </div>
                                    
                                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                        <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125h9.75a1.125 1.125 0 001.125-1.125V9.75M8.25 21h8.25" />
                                        </svg>
                                        Dashboard
                                    </a>
                                    
                                    <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                                        <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Profile Settings
                                    </a>
                                    
                                    <hr class="my-2 border-gray-100">
                                    
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200">
                                            <svg class="mr-3 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                            </svg>
                                            Sign out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <!-- Enhanced Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ $heading ?? 'Dashboard' }}</h1>
                        <p class="mt-1 text-sm text-gray-500">Welcome back! Here's what's happening with your account.</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <!-- Quick Action Buttons -->
                        @auth
                            @if(Auth::user()->role === 'seller' || Auth::user()->role === 'admin')
                                <a href="{{ route('items.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <span>New Item</span>
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <main class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="lg:grid lg:grid-cols-12 lg:gap-8">
                    <!-- Enhanced Sidebar -->
                    <aside class="lg:col-span-3">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <nav class="space-y-2">
                                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">Navigation</div>
                                
                                <a href="{{ route('dashboard') }}"
                                   class="{{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 border-transparent' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border transition-all duration-200">
                                    <svg class="text-current mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                    </svg>
                                    Dashboard Overview
                                </a>

                                <a href="{{ route('items.index') }}"
                                   class="{{ request()->routeIs('items.index') ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 border-transparent' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border transition-all duration-200">
                                    <svg class="text-current mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                    </svg>
                                    All Items
                                </a>

                                @auth
                                    <!-- Role-based Navigation -->
                                    @if(Auth::user()->role === 'seller' || Auth::user()->role === 'admin')
                                        <div class="pt-4 mt-4 border-t border-gray-200">
                                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Seller Tools</div>
                                            
                                            <a href="{{ route('items.create') }}"
                                               class="{{ request()->routeIs('items.create') ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900 border-transparent' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg border transition-all duration-200">
                                                <svg class="text-current mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Create Listing
                                            </a>

                                            <a href="{{ route('dashboard.my_listings') }}" class="text-gray-700 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200">
                                                <svg class="text-gray-400 group-hover:text-gray-500 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                                </svg>
                                                My Listings
                                            </a>
                                        </div>
                                    @endif

                                    @if(Auth::user()->role === 'admin')
                                        <div class="pt-4 mt-4 border-t border-gray-200">
                                            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Admin</div>
                                            
                                            <a href="#" class="text-gray-700 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200">
                                                <svg class="text-gray-400 group-hover:text-gray-500 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m0 0v9a3.75 3.75 0 103.75-3.75M7.5 6a3.75 3.75 0 113.75 3.75m-3.75-3.75h9.75m-9.75 10.5v9a3.75 3.75 0 103.75-3.75m-3.75-3.75h9.75" />
                                                </svg>
                                                Admin Panel
                                            </a>

                                            <a href="#" class="text-gray-700 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200">
                                                <svg class="text-gray-400 group-hover:text-gray-500 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                                </svg>
                                                User Management
                                            </a>
                                        </div>
                                    @endif
                                @endauth
                            </nav>
                        </div>
                    </aside>

                    <!-- Enhanced Main Content Area -->
                    <div class="lg:col-span-9">
                        <!-- Enhanced Flash Messages -->
                        @if (session('success'))
                            <div class="mb-6 rounded-xl bg-green-50 p-4 border border-green-200 animate-fade-in">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mb-6 rounded-xl bg-red-50 p-4 border border-red-200 animate-fade-in">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Main Content Slot -->
                        <div class="space-y-6">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Mobile Menu Toggle (for future enhancement) -->
    <div x-data="{ mobileMenuOpen: false }" class="sm:hidden">
        <!-- Mobile menu button would go here -->
    </div>
    <script>
  const beamsClient = new PusherPushNotifications.Client({
    instanceId: '1985bfbb-792c-493b-a3c1-1794f8a26d9d',
  });

  beamsClient.start()
    .then(() => beamsClient.addDeviceInterest('hello'))
    .then(() => console.log('Successfully registered and subscribed!'))
    .catch(console.error);
</script>
</body>
</html>