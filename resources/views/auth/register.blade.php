<x-layout>
    <x-slot:heading>
        Register
    </x-slot:heading>

    <form method="POST" action="{{ route('register.post') }}">
        @csrf

        <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <x-form-field>
                        <x-form-label for="username">Username</x-form-label>
                        <div class="mt-2">
                            <x-form-input name="username" id="username" autocomplete="username" required value="{{ old('username') }}" />
                            <x-form-error name="username" />
                        </div>
                    </x-form-field>

                    <x-form-field>
                        <x-form-label for="email">Email</x-form-label>
                        <div class="mt-2">
                            <x-form-input name="email" id="email" type="email" autocomplete="email" required value="{{ old('email') }}" />
                            <x-form-error name="email" />
                        </div>
                    </x-form-field>

                    <x-form-field>
                        <x-form-label for="password">Password</x-form-label>
                        <div class="mt-2">
                            <x-form-input name="password" id="password" type="password" autocomplete="new-password" required />
                            <x-form-error name="password" />
                        </div>
                    </x-form-field>

                    <x-form-field>
                        <x-form-label for="password_confirmation">Confirm Password</x-form-label>
                        <div class="mt-2">
                            <x-form-input name="password_confirmation" id="password_confirmation" type="password" autocomplete="new-password" required />
                            <x-form-error name="password_confirmation" />
                        </div>
                    </x-form-field>

                    <x-form-field>
                        <x-form-label for="role">Role</x-form-label>
                        <div class="mt-2">
                            <select id="role" name="role" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6" required>
                                <option value="">Select a role</option>
                                <option value="bidder" {{ old('role') == 'bidder' ? 'selected' : '' }}>Bidder</option>
                                <option value="seller" {{ old('role') == 'seller' ? 'selected' : '' }}>Seller</option>
                                {{-- If 'admin' role is enabled for self-registration, uncomment below --}}
                                {{-- <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option> --}}
                            </select>
                            <x-form-error name="role" />
                        </div>
                    </x-form-field>

                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="/">
                    <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
                </a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Register
                </button>
            </div>
        </div>
    </form>

    {{-- Optional: Add a link to the login page --}}
    <p class="mt-10 text-center text-sm text-gray-500">
        Already a member?
        <a href="{{ route('login') }}" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Login here</a>
    </p>

</x-layout>