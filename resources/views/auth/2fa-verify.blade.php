<x-auth-layout>
    <x-slot:heading>
        Verify Your Account
    </x-slot:heading>

    <div class="py-6">
        <p class="text-gray-700 mb-4">
            Please check your email for a 6-digit verification code.
        </p>

        <form method="POST" action="{{ route('auth.2fa.verify.post') }}">
            @csrf
            <div>
                <label for="otp" class="block text-sm font-medium leading-6 text-gray-900">Verification Code</label>
                <div class="mt-2">
                    <input id="otp" name="otp" type="text" required autofocus
                           class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </div>
                @error('otp')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500">
                    Verify
                </button>
            </div>
        </form>
    </div>
</x-auth-layout>