<x-layout>
    <x-slot:heading>
        {{ $heading }}
    </x-slot:heading>

    <div class="max-w-4xl mx-auto">
        <!-- Form Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <div class="text-center">
                <div class="mx-auto h-12 w-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mb-4">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Create New Auction Listing</h2>
                <p class="text-gray-600">Fill out the form below to list your item for auction. All required fields are marked with an asterisk (*).</p>
            </div>
        </div>

        <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data" x-data="{ 
            imagePreview: null,
            dragOver: false,
            startingBid: '{{ old('starting_bid', '') }}',
            increment: '{{ old('min_bid_increment', '') }}',
            handleFileSelect(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => this.imagePreview = e.target.result;
                    reader.readAsDataURL(file);
                }
            },
            handleDrop(event) {
                event.preventDefault();
                this.dragOver = false;
                const file = event.dataTransfer.files[0];
                if (file && file.type.startsWith('image/')) {
                    document.getElementById('image').files = event.dataTransfer.files;
                    this.handleFileSelect({ target: { files: [file] } });
                }
            },
            clearImage() {
                this.imagePreview = null;
                document.getElementById('image').value = '';
            },
            updateBidPreview() {
                this.startingBid = document.getElementById('starting_bid').value;
                this.increment = document.getElementById('min_bid_increment').value;
            }
        }">
            @csrf

            <!-- Item Details Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-8 py-6 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="h-8 w-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Item Information</h3>
                            <p class="text-sm text-gray-600">Provide detailed information about your item</p>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
                        <!-- Item Name -->
                        <div class="sm:col-span-2">
                            <label for="item_name" class="block text-sm font-semibold text-gray-900 mb-2">
                                Item Name <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       name="item_name" 
                                       id="item_name" 
                                       required 
                                       value="{{ old('item_name') }}" 
                                       placeholder="e.g., Vintage Rolex Submariner"
                                       class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-900 placeholder:text-gray-400 sm:text-sm transition-colors duration-200">
                            </div>
                            @error('item_name')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-semibold text-gray-900 mb-2">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select id="category_id" 
                                    name="category_id" 
                                    required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-900 sm:text-sm transition-colors duration-200">
                                <option value="">Choose a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->category_id }}" {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Condition -->
                        <div>
                            <label for="condition" class="block text-sm font-semibold text-gray-900 mb-2">
                                Condition <span class="text-red-500">*</span>
                            </label>
                            <select id="condition" 
                                    name="condition" 
                                    required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-900 sm:text-sm transition-colors duration-200">
                                <option value="">Select condition</option>
                                <option value="New" {{ old('condition') == 'New' ? 'selected' : '' }}>
                                    New - Never used, in original packaging
                                </option>
                                <option value="Used" {{ old('condition') == 'Used' ? 'selected' : '' }}>
                                    Used - Previously owned, shows signs of wear
                                </option>
                                <option value="Refurbished" {{ old('condition') == 'Refurbished' ? 'selected' : '' }}>
                                    Refurbished - Restored to working condition
                                </option>
                            </select>
                            @error('condition')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="sm:col-span-2">
                            <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">
                                Description
                            </label>
                            <div class="relative">
                                <textarea id="description" 
                                         name="description" 
                                         rows="5" 
                                         maxlength="1000"
                                         placeholder="Provide a detailed description of the item, including any flaws, unique features, history, or special characteristics. The more detail you provide, the more confident bidders will be."
                                         class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-900 placeholder:text-gray-400 sm:text-sm transition-colors duration-200 resize-none"
                                         x-data="{ count: 0 }"
                                         x-init="count = $el.value.length"
                                         @input="count = $el.value.length">{{ old('description') }}</textarea>
                                <div class="absolute bottom-3 right-3 text-xs text-gray-400" x-text="count + '/1000 characters'"></div>
                            </div>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Image Upload -->
                        <div class="sm:col-span-2">
                            <label for="image" class="block text-sm font-semibold text-gray-900 mb-2">
                                Item Image
                            </label>
                            
                            <!-- Image Upload Area -->
                            <div class="relative">
                                <div @dragover.prevent="dragOver = true"
                                     @dragleave.prevent="dragOver = false"
                                     @drop.prevent="handleDrop"
                                     :class="dragOver ? 'border-indigo-400 bg-indigo-50' : 'border-gray-300'"
                                     class="border-2 border-dashed rounded-lg p-8 text-center transition-colors duration-200">
                                    
                                    <!-- Preview Image -->
                                    <div x-show="imagePreview" class="mb-4">
                                        <img :src="imagePreview" alt="Preview" class="mx-auto max-h-48 rounded-lg shadow-sm">
                                        <button type="button" 
                                                @click="clearImage()"
                                                class="mt-3 text-sm text-red-600 hover:text-red-800 font-medium">
                                            Remove Image
                                        </button>
                                    </div>

                                    <!-- Upload Area -->
                                    <div x-show="!imagePreview">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <div class="text-sm text-gray-600">
                                            <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                                <span>Upload an image</span>
                                                <input id="image" 
                                                       name="image" 
                                                       type="file" 
                                                       accept="image/*"
                                                       @change="handleFileSelect"
                                                       class="sr-only">
                                            </label>
                                            <span class="pl-1">or drag and drop</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF up to 2MB</p>
                                    </div>
                                </div>
                            </div>
                            
                            @error('image')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Auction Parameters Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-8 py-6 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="h-8 w-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Auction Settings</h3>
                            <p class="text-sm text-gray-600">Configure bidding rules and auction duration</p>
                        </div>
                    </div>
                </div>

                <div class="p-8">
                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                        <!-- Starting Bid -->
                        <div>
                            <label for="starting_bid" class="block text-sm font-semibold text-gray-900 mb-2">
                                Starting Bid <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" 
                                       name="starting_bid" 
                                       id="starting_bid" 
                                       step="0.01" 
                                       min="0.01" 
                                       required 
                                       value="{{ old('starting_bid') }}" 
                                       placeholder="50.00"
                                       @input="updateBidPreview()"
                                       class="block w-full pl-7 pr-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-900 placeholder:text-gray-400 sm:text-sm transition-colors duration-200">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">The initial bidding price</p>
                            @error('starting_bid')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Min Bid Increment -->
                        <div>
                            <label for="min_bid_increment" class="block text-sm font-semibold text-gray-900 mb-2">
                                Bid Increment <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" 
                                       name="min_bid_increment" 
                                       id="min_bid_increment" 
                                       step="0.01" 
                                       min="0.01" 
                                       required 
                                       value="{{ old('min_bid_increment') }}" 
                                       placeholder="5.00"
                                       @input="updateBidPreview()"
                                       class="block w-full pl-7 pr-3 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-900 placeholder:text-gray-400 sm:text-sm transition-colors duration-200">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Minimum amount between bids</p>
                            @error('min_bid_increment')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Auction End Time -->
                        <div class="sm:col-span-2 lg:col-span-1">
                            <label for="auction_end_time" class="block text-sm font-semibold text-gray-900 mb-2">
                                Auction End Date <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" 
                                   name="auction_end_time" 
                                   id="auction_end_time" 
                                   required 
                                   value="{{ old('auction_end_time') }}"
                                   min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                                   class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-gray-900 sm:text-sm transition-colors duration-200">
                            <p class="mt-1 text-xs text-gray-500">When the auction will close</p>
                            @error('auction_end_time')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Auction Preview -->
                    <div class="mt-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center mb-4">
                            <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <h4 class="text-sm font-medium text-gray-900">Auction Preview</h4>
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Starting bid:</span>
                                <span class="ml-2 font-medium text-gray-900" x-text="startingBid ? '$' + parseFloat(startingBid).toFixed(2) : '$0.00'"></span>
                            </div>
                            <div>
                                <span class="text-gray-500">Next bid minimum:</span>
                                <span class="ml-2 font-medium text-gray-900" x-text="(startingBid && increment) ? '$' + (parseFloat(startingBid) + parseFloat(increment)).toFixed(2) : '$0.00'"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Please review all information before submitting</span>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('items.index') }}" 
                           class="px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                            Cancel
                        </a>
                        
                        <button type="submit" 
                                class="px-8 py-3 text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm hover:shadow-md transition-all duration-200 flex items-center space-x-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>Create Auction Listing</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Set minimum date for auction end time to 1 hour from now
        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            now.setHours(now.getHours() + 1);
            const minDateTime = now.toISOString().slice(0, 16);
            document.getElementById('auction_end_time').min = minDateTime;
        });
    </script>
</x-layout>