<x-layout>
    <x-slot:heading>
        {{ $heading }} {{-- Should be something like "Edit Item: {{ $item->item_name }}" --}}
    </x-slot:heading>

    {{-- Make sure enctype="multipart/form-data" is added for file uploads --}}
    <form method="POST" action="{{ route('items.update', $item) }}" class="space-y-8" enctype="multipart/form-data">
        @csrf
        @method('PUT') 

        {{-- Section: Item Details --}}
        <div class="border-b border-gray-900/10 pb-12">
            <h2 class="text-base font-semibold leading-7 text-gray-900">Item Details</h2>
            <p class="mt-1 text-sm leading-6 text-gray-600">Update basic information about the item.</p>

            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                {{-- Item Name --}}
                <x-form-field class="sm:col-span-4">
                    <x-form-label for="item_name">Item Name</x-form-label>
                    <div class="mt-2">
                        <x-form-input name="item_name" id="item_name" required value="{{ old('item_name', $item->item_name) }}" placeholder="e.g., Vintage Rolex Submariner" />
                        <x-form-error name="item_name" />
                    </div>
                </x-form-field>

                {{-- Category --}}
                <x-form-field class="sm:col-span-2">
                    <x-form-label for="category_id">Category</x-form-label>
                    <div class="mt-2">
                        <select id="category_id" name="category_id" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->category_id }}" {{ old('category_id', $item->category_id) == $category->category_id ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                        <x-form-error name="category_id" />
                    </div>
                </x-form-field>

                {{-- Description --}}
                <x-form-field class="sm:col-span-full">
                    <x-form-label for="description">Description</x-form-label>
                    <div class="mt-2">
                        <textarea id="description" name="description" rows="5" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Provide a detailed description of the item, including any flaws or unique features.">{{ old('description', $item->description) }}</textarea>
                        <x-form-error name="description" />
                    </div>
                </x-form-field>

                {{-- Condition --}}
                <x-form-field class="sm:col-span-3">
                    <x-form-label for="condition">Condition</x-form-label>
                    <div class="mt-2">
                        <select id="condition" name="condition" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6" required>
                            <option value="">Select Condition</option>
                            <option value="New" {{ old('condition', $item->condition) == 'New' ? 'selected' : '' }}>New</option>
                            <option value="Used" {{ old('condition', $item->condition) == 'Used' ? 'selected' : '' }}>Used</option>
                            <option value="Refurbished" {{ old('condition', $item->condition) == 'Refurbished' ? 'selected' : '' }}>Refurbished</option>
                        </select>
                        <x-form-error name="condition" />
                    </div>
                </x-form-field>

                {{-- Image File Input --}}
                <x-form-field class="sm:col-span-3">
                    <x-form-label for="image">Image (Optional)</x-form-label>
                    <div class="mt-2">
                        @if($item->image_path)
                            <p class="mb-2 text-sm text-gray-600">Current Image:</p>
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->item_name }}" class="w-32 h-32 object-cover rounded-md mb-4 border border-gray-300">
                            <p class="mb-2 text-sm text-gray-600">Upload a new image to replace the current one:</p>
                        @endif
                        <input type="file" name="image" id="image" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 focus:outline-none" accept="image/*">
                        @error('image')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Max 2MB, JPG, PNG, GIF. Leave blank to keep current image.</p>
                    </div>
                </x-form-field>
            </div>
        </div>

        {{-- Section: Auction Details --}}
        <div class="border-b border-gray-900/10 pb-12">
            <h2 class="text-base font-semibold leading-7 text-gray-900">Auction Parameters</h2>
            <p class="mt-1 text-sm leading-6 text-gray-600">Update bidding rules and duration for your item.</p>

            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                {{-- Starting Bid --}}
                <x-form-field class="sm:col-span-3">
                    <x-form-label for="starting_bid">Starting Bid ($)</x-form-label>
                    <div class="mt-2">
                        <x-form-input name="starting_bid" id="starting_bid" type="number" step="0.01" min="0.01" required value="{{ old('starting_bid', $item->starting_bid) }}" placeholder="e.g., 50.00" />
                        <x-form-error name="starting_bid" />
                    </div>
                </x-form-field>

                {{-- Min Bid Increment --}}
                <x-form-field class="sm:col-span-3">
                    <x-form-label for="min_bid_increment">Min Bid Increment ($)</x-form-label>
                    <div class="mt-2">
                        <x-form-input name="min_bid_increment" id="min_bid_increment" type="number" step="0.01" min="0.01" required value="{{ old('min_bid_increment', $item->min_bid_increment) }}" placeholder="e.g., 5.00" />
                        <x-form-error name="min_bid_increment" />
                    </div>
                </x-form-field>

                {{-- Auction End Time --}}
                <x-form-field class="sm:col-span-3">
                    <x-form-label for="auction_end_time">Auction End Time</x-form-label>
                    <div class="mt-2">
                        {{-- Format datetime-local for existing value --}}
                        <x-form-input name="auction_end_time" id="auction_end_time" type="datetime-local" required value="{{ old('auction_end_time', \Carbon\Carbon::parse($item->auction_end_time)->format('Y-m-d\TH:i')) }}" />
                        <x-form-error name="auction_end_time" />
                    </div>
                </x-form-field>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="mt-6 flex items-center justify-end gap-x-6">
            {{-- Cancel Button --}}
            <a href="{{ route('items.show', $item) }}" class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-700">
                Cancel
            </a>
            {{-- Update Button --}}
            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Update Item
            </button>
        </div>
    </form>

    {{-- Delete Form --}}
    <form method="POST" action="{{ route('items.destroy', $item) }}" class="mt-6">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-semibold leading-6" onclick="return confirm('Are you sure you want to delete this item? This action cannot be undone.');">
            Delete Item
        </button>
    </form>

</x-layout>