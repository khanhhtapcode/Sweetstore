<x-admin-layout>
    <x-slot name="header">
        Sửa sản phẩm
    </x-slot>

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')
@section('page-description', 'Update product information')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">Edit: {{ $product->name }}</h2>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.products.show', $product) }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-200">
                            <i class="fas fa-eye mr-2"></i>
                            View Product
                        </a>
                        <a href="{{ route('admin.products.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Products
                        </a>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Product Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Product Name *
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $product->name) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                   placeholder="Enter product name"
                                   required>
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Category *
                            </label>
                            <select id="category_id"
                                    name="category_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('category_id') border-red-500 @enderror"
                                    required>
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                Price ($) *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number"
                                       id="price"
                                       name="price"
                                       value="{{ old('price', $product->price) }}"
                                       step="0.01"
                                       min="0"
                                       class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('price') border-red-500 @enderror"
                                       placeholder="0.00"
                                       required>
                            </div>
                            @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stock Quantity -->
                        <div>
                            <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Stock Quantity *
                            </label>
                            <input type="number"
                                   id="stock_quantity"
                                   name="stock_quantity"
                                   value="{{ old('stock_quantity', $product->stock_quantity) }}"
                                   min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('stock_quantity') border-red-500 @enderror"
                                   placeholder="Enter stock quantity"
                                   required>
                            @error('stock_quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status
                            </label>
                            <div class="flex items-center space-x-6">
                                <label class="flex items-center">
                                    <input type="radio"
                                           name="status"
                                           value="active"
                                           {{ old('status', $product->status) === 'active' ? 'checked' : '' }}
                                           class="w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500">
                                    <span class="ml-2 text-sm text-gray-700">Active</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio"
                                           name="status"
                                           value="inactive"
                                           {{ old('status', $product->status) === 'inactive' ? 'checked' : '' }}
                                           class="w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500">
                                    <span class="ml-2 text-sm text-gray-700">Inactive</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Current Image -->
                        @if($product->image)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Current Image
                                </label>
                                <div class="relative">
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-48 object-cover rounded-lg border border-gray-200">
                                    <div class="absolute top-2 right-2">
                                        <button type="button"
                                                onclick="removeCurrentImage()"
                                                class="bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition-colors">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Product Image -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $product->image ? 'Update Image' : 'Product Image' }}
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-purple-400 transition-colors">
                                <input type="file"
                                       id="image"
                                       name="image"
                                       accept="image/*"
                                       class="hidden"
                                       onchange="previewImage(this)">
                                <label for="image" class="cursor-pointer">
                                    <div id="image-preview" class="hidden">
                                        <img id="preview-img" class="w-full h-48 object-cover rounded-lg mb-4">
                                    </div>
                                    <div id="upload-placeholder">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                        <p class="text-gray-600 mb-2">Click to upload or drag and drop</p>
                                        <p class="text-sm text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                    </div>
                                </label>
                            </div>
                            @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Short Description -->
                        <div>
                            <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Short Description
                            </label>
                            <textarea id="short_description"
                                      name="short_description"
                                      rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('short_description') border-red-500 @enderror"
                                      placeholder="Brief description of the product">{{ old('short_description', $product->short_description) }}</textarea>
                            @error('short_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Full Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Full Description
                    </label>
                    <textarea id="description"
                              name="description"
                              rows="6"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('description') border-red-500 @enderror"
                              placeholder="Detailed description of the product, ingredients, etc.">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.products.index') }}"
                       class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                    document.getElementById('upload-placeholder').classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeCurrentImage() {
            if (confirm('Are you sure you want to remove the current image?')) {
                // Add hidden input to mark image for removal
                const form = document.querySelector('form');
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'remove_image';
                input.value = '1';
                form.appendChild(input);

                // Hide the current image
                event.target.closest('div').parentElement.style.display = 'none';
            }
        }
    </script>
</x-admin-layout>
