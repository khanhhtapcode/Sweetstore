<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Chi Tiết Danh Mục: ') . $category->name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.categories.edit', $category) }}"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Chỉnh Sửa
                </a>
                <a href="{{ route('admin.categories.index') }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Quay Lại
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Category Info -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Category Image -->
                    <div>
                        @if($category->image_url)
                            <img src="{{ $category->image_url }}"
                                 alt="{{ $category->name }}"
                                 class="w-full h-64 object-cover rounded-lg">
                        @else
                            <div class="w-full h-64 bg-gradient-to-br from-pink-100 to-purple-100 rounded-lg flex items-center justify-center">
                                <span class="text-6xl">📁</span>
                            </div>
                        @endif
                    </div>

                    <!-- Category Details -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tên Danh Mục</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $category->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mô Tả</label>
                            <p class="mt-1 text-gray-600">{{ $category->description ?: 'Không có mô tả' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Trạng Thái</label>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $category->is_active ? 'Hoạt động' : 'Tạm dừng' }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Số Sản Phẩm</label>
                            <p class="mt-1 text-lg font-semibold text-blue-600">{{ $category->products->count() }} sản phẩm</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ngày Tạo</label>
                            <p class="mt-1 text-gray-600">{{ $category->created_at->format('d/m/Y H:i') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cập Nhật Lần Cuối</label>
                            <p class="mt-1 text-gray-600">{{ $category->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products in Category -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900">
                        Sản Phẩm Trong Danh Mục ({{ $category->products->count() }})
                    </h3>
                    <a href="{{ route('admin.products.create', ['category_id' => $category->id]) }}"
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                        + Thêm Sản Phẩm
                    </a>
                </div>

                @if($category->products->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sản Phẩm
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Giá
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tồn Kho
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Trạng Thái
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Thao Tác
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($category->products as $product)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($product->image_url)
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-lg object-cover" src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                                </div>
                                            @endif
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $product->name }}
                                                    @if($product->is_featured)
                                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                ⭐ Nổi bật
                                                            </span>
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ Str::limit($product->description, 50) }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $product->formatted_price }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $product->stock_quantity <= 5 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $product->stock_quantity }} sản phẩm
                                            </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $product->is_active ? 'Hoạt động' : 'Tạm dừng' }}
                                            </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.products.show', $product) }}"
                                               class="text-blue-600 hover:text-blue-900">Xem</a>
                                            <a href="{{ route('admin.products.edit', $product) }}"
                                               class="text-indigo-600 hover:text-indigo-900">Sửa</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có sản phẩm nào</h3>
                        <p class="mt-1 text-sm text-gray-500">Bắt đầu bằng việc thêm sản phẩm cho danh mục này.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.products.create') }}"
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
