<x-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-center text-gray-800">Verify Your Email Address</h2>

            @if (session('status') == 'verification-link-sent')
                <div class="p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    A new verification link has been sent to the email address you provided during registration.
                </div>
            @endif

            <p class="text-sm text-center text-gray-600">
                Before proceeding, please check your email for a verification link. If you did not receive the email,
            </p>

            <form class="space-y-4" method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                    class="flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Click here to request another
                </button>
            </form>
        </div>
    </div>
</x-layout>