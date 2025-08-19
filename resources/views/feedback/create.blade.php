<x-layout>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow rounded-lg">
        <h2 class="text-2xl font-semibold mb-4">
            Give Feedback for "{{ $item->name }}"
        </h2>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 border border-red-300 text-red-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('feedback.store', $item) }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block font-medium">Rating (1-5)</label>
                <input 
                    type="number" 
                    name="rating" 
                    min="1" 
                    max="5" 
                    required
                    class="w-20 border rounded p-2"
                >
            </div>

            <div>
                <label class="block font-medium">Comment</label>
                <textarea 
                    name="comment" 
                    rows="4" 
                    maxlength="500"
                    class="w-full border rounded p-2"
                ></textarea>
            </div>

            <button 
                type="submit" 
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
            >
                Submit Feedback
            </button>
        </form>
    </div>
</x-layout>
