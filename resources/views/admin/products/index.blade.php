<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Quản Lý Sản Phẩm') }}
            </h2>
            <div class="ml-4">
                <a href="{{ route('admin.products.create') }}"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    + Thêm Sản Phẩm Mới
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Filters and Search -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-wrap gap-4">
                        <!-- Search -->
                        <div class="flex-1 min-w-64">
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Tìm kiếm sản phẩm..."
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Tất cả danh mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Tất cả trạng thái</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                                <option value="featured" {{ request('status') == 'featured' ? 'selected' : '' }}>Nổi bật</option>
                                <option value="low_stock" {{ request('status') == 'low_stock' ? 'selected' : '' }}>Sắp hết hàng</option>
                            </select>
                        </div>

                        <!-- Sort -->
                        <div>
                            <select name="sort" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Giá thấp đến cao</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Giá cao đến thấp</option>
                                <option value="stock" {{ request('sort') == 'stock' ? 'selected' : '' }}>Tồn kho</option>
                            </select>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex gap-2">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Lọc
                            </button>

                            @if(request()->hasAny(['search', 'category', 'status', 'sort']))
                                <a href="{{ route('admin.products.index') }}"
                                   class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Xóa bộ lọc
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($products->count() > 0)
                        <!-- Bulk Actions -->
                        <div id="bulk-actions" class="hidden mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-blue-700">
                                    <span id="selected-count">0</span> sản phẩm được chọn
                                </span>
                                <div class="flex space-x-2">
                                    <button onclick="bulkAction('activate')" class="px-3 py-1 bg-green-100 text-green-700 rounded text-sm hover:bg-green-200">
                                        Kích hoạt
                                    </button>
                                    <button onclick="bulkAction('deactivate')" class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded text-sm hover:bg-yellow-200">
                                        Tạm dừng
                                    </button>
                                    <button onclick="bulkAction('feature')" class="px-3 py-1 bg-purple-100 text-purple-700 rounded text-sm hover:bg-purple-200">
                                        Đặt nổi bật
                                    </button>
                                    <button onclick="bulkAction('unfeature')" class="px-3 py-1 bg-gray-100 text-gray-700 rounded text-sm hover:bg-gray-200">
                                        Bỏ nổi bật
                                    </button>
                                    <button onclick="bulkAction('delete')" class="px-3 py-1 bg-red-100 text-red-700 rounded text-sm hover:bg-red-200">
                                        Xóa
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Results Info -->
                        <div class="flex justify-between items-center mb-4">
                            <p class="text-gray-600">
                                Hiển thị {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}
                                trong {{ $products->total() }} sản phẩm
                            </p>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.products.export') }}"
                                   class="px-3 py-1 bg-green-100 text-green-700 rounded text-sm hover:bg-green-200">
                                    📊 Xuất Excel
                                </a>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <input type="checkbox" class="rounded border-gray-300" id="select-all">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Sản Phẩm
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Danh Mục
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
                                        Ngày Tạo
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Thao Tác
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($products as $product)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" class="rounded border-gray-300 product-checkbox" value="{{ $product->id }}">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($product->image_url)
                                                    <div class="flex-shrink-0 h-12 w-12">
                                                        <img class="h-12 w-12 rounded-lg object-cover" src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                                    </div>
                                                @else
                                                    <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-pink-100 to-purple-100 rounded-lg flex items-center justify-center">
                                                        <span class="text-lg">🧁</span>
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
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $product->category->name ?? 'Chưa phân loại' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $product->formatted_price }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="text-sm font-medium text-gray-900 mr-2">{{ $product->stock_quantity ?? 0 }}</span>
                                                @if(($product->stock_quantity ?? 0) <= 5)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        ⚠️ Sắp hết
                                                    </span>
                                                @elseif(($product->stock_quantity ?? 0) <= 20)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        ⚡ Ít
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        ✅ Đủ
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col gap-1">
                                                <button onclick="toggleProductStatus({{ $product->id }})"
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium cursor-pointer transition-colors
                                                            {{ $product->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                                    {{ $product->is_active ? '✅ Hoạt động' : '❌ Tạm dừng' }}
                                                </button>
                                                @if($product->is_featured)
                                                    <button onclick="toggleProductFeatured({{ $product->id }})"
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 hover:bg-yellow-200 cursor-pointer transition-colors">
                                                        ⭐ Nổi bật
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <div class="flex flex-col">
                                                <span>{{ $product->created_at->format('d/m/Y') }}</span>
                                                <span class="text-xs text-gray-400">{{ $product->created_at->diffForHumans() }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.products.show', $product) }}"
                                                   class="text-blue-600 hover:text-blue-900 transition-colors"
                                                   title="Xem chi tiết">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product) }}"
                                                   class="text-indigo-600 hover:text-indigo-900 transition-colors"
                                                   title="Chỉnh sửa">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>

                                                <!-- Quick Stock Update -->
                                                <button onclick="updateStock({{ $product->id }}, {{ $product->stock_quantity ?? 0 }})"
                                                        class="text-purple-600 hover:text-purple-900 transition-colors"
                                                        title="Cập nhật tồn kho">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                    </svg>
                                                </button>

                                                <!-- Toggle Featured -->
                                                <button onclick="toggleProductFeatured({{ $product->id }})"
                                                        class="text-yellow-600 hover:text-yellow-900 transition-colors"
                                                        title="{{ $product->is_featured ? 'Bỏ nổi bật' : 'Đặt nổi bật' }}">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                    </svg>
                                                </button>

                                                <form action="{{ route('admin.products.destroy', $product) }}"
                                                      method="POST"
                                                      class="inline"
                                                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-red-600 hover:text-red-900 transition-colors"
                                                            title="Xóa sản phẩm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($products->hasPages())
                            <div class="mt-4">
                                {{ $products->withQueryString()->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có sản phẩm nào</h3>
                            <p class="mt-1 text-sm text-gray-500">Bắt đầu bằng việc tạo sản phẩm đầu tiên.</p>
                            <div class="mt-6">
                                <a href="{{ route('admin.products.create') }}"
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Thêm Sản Phẩm
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Update Modal -->
    <div id="stockModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Cập Nhật Tồn Kho</h3>
                <form id="stockForm">
                    <input type="hidden" id="productId">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hành động</label>
                        <select id="stockAction" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="set">Đặt số lượng mới</option>
                            <option value="add">Thêm vào kho</option>
                            <option value="subtract">Trừ khỏi kho</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Số lượng</label>
                        <input type="number" id="stockQuantity" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeStockModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Hủy
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Cập Nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Bulk selection functionality
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('select-all');
            const productCheckboxes = document.querySelectorAll('.product-checkbox');
            const bulkActions = document.getElementById('bulk-actions');
            const selectedCount = document.getElementById('selected-count');

            selectAll.addEventListener('change', function() {
                productCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActions();
            });

            productCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateBulkActions);
            });

            function updateBulkActions() {
                const selectedProducts = document.querySelectorAll('.product-checkbox:checked');
                const count = selectedProducts.length;

                selectedCount.textContent = count;

                if (count > 0) {
                    bulkActions.classList.remove('hidden');
                } else {
                    bulkActions.classList.add('hidden');
                }

                // Update select all checkbox
                selectAll.indeterminate = count > 0 && count < productCheckboxes.length;
                selectAll.checked = count === productCheckboxes.length;
            }
        });

        // Toggle product status
        function toggleProductStatus(productId) {
            if (!confirm('Bạn có chắc muốn thay đổi trạng thái sản phẩm này?')) return;

            fetch(`/admin/products/${productId}/toggle-active`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Có lỗi xảy ra');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra');
                });
        }

        // Toggle product featured
        function toggleProductFeatured(productId) {
            fetch(`/admin/products/${productId}/toggle-featured`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Có lỗi xảy ra');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra');
                });
        }

        // Update stock modal
        function updateStock(productId, currentStock) {
            document.getElementById('productId').value = productId;
            document.getElementById('stockQuantity').value = currentStock;
            document.getElementById('stockModal').classList.remove('hidden');
        }

        function closeStockModal() {
            document.getElementById('stockModal').classList.add('hidden');
        }

        // Handle stock form submission
        document.getElementById('stockForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const productId = document.getElementById('productId').value;
            const action = document.getElementById('stockAction').value;
            const quantity = document.getElementById('stockQuantity').value;

            fetch(`/admin/products/${productId}/update-stock`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    action: action,
                    stock_quantity: parseInt(quantity)
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeStockModal();
                        location.reload();
                    } else {
                        alert(data.message || 'Có lỗi xảy ra');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra');
                });
        });

        // Bulk actions
        function bulkAction(action) {
            const selectedProducts = Array.from(document.querySelectorAll('.product-checkbox:checked')).map(cb => cb.value);

            if (selectedProducts.length === 0) {
                alert('Vui lòng chọn ít nhất một sản phẩm');
                return;
            }

            let confirmMessage = '';
            switch(action) {
                case 'activate':
                    confirmMessage = `Kích hoạt ${selectedProducts.length} sản phẩm đã chọn?`;
                    break;
                case 'deactivate':
                    confirmMessage = `Tạm dừng ${selectedProducts.length} sản phẩm đã chọn?`;
                    break;
                case 'feature':
                    confirmMessage = `Đặt ${selectedProducts.length} sản phẩm đã chọn làm nổi bật?`;
                    break;
                case 'unfeature':
                    confirmMessage = `Bỏ nổi bật cho ${selectedProducts.length} sản phẩm đã chọn?`;
                    break;
                case 'delete':
                    confirmMessage = `Xóa ${selectedProducts.length} sản phẩm đã chọn?\nHành động này không thể hoàn tác!`;
                    break;
            }

            if (!confirm(confirmMessage)) return;

            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/products/bulk-action';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;

            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = action;

            form.appendChild(csrfToken);
            form.appendChild(actionInput);

            selectedProducts.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'product_ids[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }

        // Close modal when clicking outside
        document.getElementById('stockModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeStockModal();
            }
        });
    </script>
</x-admin-layout>
