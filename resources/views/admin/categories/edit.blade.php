<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Chỉnh Sửa Danh Mục
            </h2>
            <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Quay Lại
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tên Danh Mục <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $category->name) }}"
                                           required>
                                    @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Mô Tả
                                    </label>
                                    <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"
                                              id="description"
                                              name="description"
                                              rows="4">{{ old('description', $category->description) }}</textarea>
                                    @error('description')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="image_url" class="block text-sm font-medium text-gray-700 mb-2">
                                        URL Hình Ảnh
                                    </label>
                                    <input type="url"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('image_url') border-red-500 @enderror"
                                           id="image_url"
                                           name="image_url"
                                           value="{{ old('image_url', $category->image_url) }}"
                                           placeholder="https://example.com/image.jpg">
                                    @error('image_url')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <div class="mb-4">
                                    <label class="flex items-center">
                                        <input type="checkbox"
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                               name="is_active"
                                               value="1"
                                            {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-600">Kích hoạt danh mục</span>
                                    </label>
                                </div>

                                @if($category->image_url)
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Hình Ảnh Hiện Tại
                                        </label>
                                        <img src="{{ $category->image_url }}"
                                             alt="{{ $category->name }}"
                                             class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                                    </div>
                                @endif

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Thông Tin
                                    </label>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="text-sm text-gray-600 mb-2">
                                            <strong>Số sản phẩm:</strong> {{ $category->products_count ?? 0 }}
                                        </p>
                                        <p class="text-sm text-gray-600 mb-2">
                                            <strong>Ngày tạo:</strong> {{ $category->created_at->format('d/m/Y H:i') }}
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <strong>Cập nhật lần cuối:</strong> {{ $category->updated_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4 mt-6">
                            <a href="{{ route('admin.categories.index') }}"
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Hủy
                            </a>
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Cập Nhật Danh Mục
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
