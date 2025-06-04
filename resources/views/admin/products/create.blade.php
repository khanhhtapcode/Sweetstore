<x-admin-layout>
    <x-slot name="header">
        Thêm Sản Phẩm Mới
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">Thêm Sản Phẩm Mới</h2>
                    <a href="{{ route('admin.products.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Quay Lại
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Có lỗi xảy ra:</h3>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Product Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Tên Sản Phẩm *
                            </label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                   placeholder="Nhập tên sản phẩm"
                                   required>
                            @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Danh Mục *
                            </label>
                            <select id="category_id"
                                    name="category_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('category_id') border-red-500 @enderror"
                                    required>
                                <option value="">Chọn danh mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                Giá (VNĐ) *
                            </label>
                            <div class="relative">
                                <input type="number"
                                       id="price"
                                       name="price"
                                       value="{{ old('price') }}"
                                       step="1000"
                                       min="0"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('price') border-red-500 @enderror"
                                       placeholder="0"
                                       required>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₫</span>
                                </div>
                            </div>
                            @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stock Quantity -->
                        <div>
                            <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                Số Lượng Tồn Kho *
                            </label>
                            <input type="number"
                                   id="stock_quantity"
                                   name="stock_quantity"
                                   value="{{ old('stock_quantity') }}"
                                   min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('stock_quantity') border-red-500 @enderror"
                                   placeholder="Nhập số lượng"
                                   required>
                            @error('stock_quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Checkboxes -->
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', 1) ? 'checked' : '' }}
                                       class="h-4 w-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                <label for="is_active" class="ml-3 text-sm font-medium text-gray-700">
                                    Kích hoạt sản phẩm
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox"
                                       id="is_featured"
                                       name="is_featured"
                                       value="1"
                                       {{ old('is_featured') ? 'checked' : '' }}
                                       class="h-4 w-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                <label for="is_featured" class="ml-3 text-sm font-medium text-gray-700">
                                    Sản phẩm nổi bật
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Product Image -->
                        <div>
                            <label for="image_url" class="block text-sm font-medium text-gray-700 mb-2">
                                URL Hình Ảnh
                            </label>
                            <input type="url"
                                   id="image_url"
                                   name="image_url"
                                   value="{{ old('image_url') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('image_url') border-red-500 @enderror"
                                   placeholder="https://example.com/image.jpg">
                            @error('image_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Nhập URL hình ảnh hoặc để trống</p>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Mô Tả Sản Phẩm
                            </label>
                            <textarea id="description"
                                      name="description"
                                      rows="8"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent @error('description') border-red-500 @enderror"
                                      placeholder="Mô tả chi tiết về sản phẩm...">{{ old('description') }}</textarea>
                            @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Image Preview -->
                        <div id="image-preview" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Xem Trước Hình Ảnh</label>
                            <img id="preview-img" class="w-full h-48 object-cover rounded-lg border border-gray-200" alt="Preview">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.products.index') }}"
                       class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Hủy
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Tạo Sản Phẩm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Image URL preview
        document.getElementById('image_url').addEventListener('input', function() {
            const url = this.value;
            const preview = document.getElementById('image-preview');
            const img = document.getElementById('preview-img');

            if (url) {
                img.src = url;
                preview.classList.remove('hidden');

                img.onerror = function() {
                    preview.classList.add('hidden');
                };
            } else {
                preview.classList.add('hidden');
            }
        });
    </script>
</x-admin-layout>
