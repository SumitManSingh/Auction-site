<x-layout>
    <x-slot:heading>
        Log In
    </x-slot:heading>

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                    <x-form-field>
                        <x-form-label for="email">Email</x-form-label>
                        <div class="mt-2">
                            <x-form-input name="email" id="email" type="email" autocomplete="email" :value="old('email')" required />
                            <x-form-error name="email" />
                        </div>
                    </x-form-field>

                    <x-form-field>
                        <div class="flex items-center justify-between">
                            <x-form-label for="password">Password</x-form-label>
                            @if (Route::has('password.request'))
                                <div class="text-sm">
                                    <a href="{{ route('password.request') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">
                                        Forgot password?
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="mt-2">
                            <x-form-input name="password" id="password" type="password" autocomplete="current-password" required />
                            <x-form-error name="password" />
                        </div>
                    </x-form-field>
                    
                    {{-- Added "Remember Me" checkbox --}}
                    <div class="sm:col-span-full">
                        <div class="relative flex items-start">
                            <div class="flex h-6 items-center">
                                <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                            </div>
                            <div class="ml-3 text-sm leading-6">
                                <label for="remember_me" class="font-medium text-gray-900">Remember me</label>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="/">
                    <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
                </a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Log In
                </button>
            </div>
        </div>
    </form>

    {{-- Optional: Add a link to the registration page --}}
    <p class="mt-10 text-center text-sm text-gray-500">
        Not a member?
        <a href="{{ route('register') }}" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Register here</a>
    </p>

</x-layout>
