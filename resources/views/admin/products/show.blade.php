<x-admin-layout>
    <x-slot name="header">
        Chi Tiết Sản Phẩm
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h2>
                            <div class="flex items-center space-x-4 mt-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fas fa-circle w-2 h-2 mr-2"></i>
                                {{ $product->is_active ? 'Hoạt động' : 'Tạm dừng' }}
                            </span>
                                <span class="text-sm text-gray-500">
                                Tạo {{ $product->created_at->diffForHumans() }}
                            </span>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700">
                                <i class="fas fa-edit mr-2"></i>
                                Chỉnh Sửa
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">
                                    <i class="fas fa-trash mr-2"></i>
                                    Xóa
                                </button>
                            </form>
                            <a href="{{ route('admin.products.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Quay Lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Product Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Thông Tin Cơ Bản</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Tên Sản Phẩm</label>
                                    <p class="text-lg font-medium text-gray-900">{{ $product->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Danh Mục</label>
                                    <div class="flex items-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-tag mr-2"></i>
                                        {{ $product->category->name ?? 'Chưa phân loại' }}
                                    </span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Giá</label>
                                    <p class="text-2xl font-bold text-green-600">{{ $product->formatted_price }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-1">Số Lượng Tồn Kho</label>
                                    <div class="flex items-center">
                                        <span class="text-lg font-medium text-gray-900 mr-2">{{ $product->stock_quantity ?? 0 }}</span>
                                        @if(($product->stock_quantity ?? 0) <= 10)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            Sắp Hết Hàng
                                        </span>
                                        @elseif(($product->stock_quantity ?? 0) <= 50)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Tồn Kho Vừa
                                        </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Còn Hàng
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Descriptions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Mô Tả Sản Phẩm</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            @if($product->description)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 mb-2">Mô Tả Chi Tiết</label>
                                    <div class="text-gray-700 bg-gray-50 p-4 rounded-lg prose max-w-none">
                                        {!! nl2br(e($product->description)) !!}
                                    </div>
                                </div>
                            @endif

                            @if(!$product->description)
                                <div class="text-center py-8">
                                    <i class="fas fa-file-alt text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">Chưa có mô tả</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Sales Analytics -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Phân Tích Bán Hàng</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="text-center p-4 bg-blue-50 rounded-lg">
                                    <i class="fas fa-shopping-cart text-2xl text-blue-600 mb-2"></i>
                                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_orders'] ?? 0 }}</p>
                                    <p class="text-sm text-gray-500">Tổng Đơn Hàng</p>
                                </div>
                                <div class="text-center p-4 bg-green-50 rounded-lg">
                                    <i class="fas fa-money-bill-wave text-2xl text-green-600 mb-2"></i>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format(($stats['total_revenue'] ?? 0), 0, ',', '.') }}₫</p>
                                    <p class="text-sm text-gray-500">Tổng Doanh Thu</p>
                                </div>
                                <div class="text-center p-4 bg-purple-50 rounded-lg">
                                    <i class="fas fa-eye text-2xl text-purple-600 mb-2"></i>
                                    <p class="text-2xl font-bold text-gray-900">{{ $stats['views'] ?? 0 }}</p>
                                    <p class="text-sm text-gray-500">Lượt Xem</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Product Image -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Hình Ảnh Sản Phẩm</h3>
                        </div>
                        <div class="p-6">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-64 object-cover rounded-lg border border-gray-200">
                            @else
                                <div class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                                    <div class="text-center">
                                        <i class="fas fa-image text-4xl text-gray-400 mb-4"></i>
                                        <p class="text-gray-500">Chưa có hình ảnh</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Thông Tin Nhanh</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Ngày Tạo</span>
                                <span class="text-sm font-medium text-gray-900">{{ $product->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Cập Nhật Lần Cuối</span>
                                <span class="text-sm font-medium text-gray-900">{{ $product->updated_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Trạng Thái</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->is_active ? 'Hoạt động' : 'Tạm dừng' }}
                            </span>
                            </div>
                            @if($product->is_featured)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Nổi Bật</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        ⭐ Có
                                    </span>
                                </div>
                            @endif
                            <hr class="my-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">ID Sản Phẩm</span>
                                <span class="text-sm font-mono text-gray-900">#{{ $product->id }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Thao Tác Nhanh</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700">
                                <i class="fas fa-edit mr-2"></i>
                                Chỉnh Sửa Sản Phẩm
                            </a>

                            @if($product->is_active)
                                <button onclick="toggleProductStatus({{ $product->id }})"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700">
                                    <i class="fas fa-pause mr-2"></i>
                                    Tạm Dừng Sản Phẩm
                                </button>
                            @else
                                <button onclick="toggleProductStatus({{ $product->id }})"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700">
                                    <i class="fas fa-play mr-2"></i>
                                    Kích Hoạt Sản Phẩm
                                </button>
                            @endif

                            <button onclick="toggleProductFeatured({{ $product->id }})"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700">
                                <i class="fas fa-star mr-2"></i>
                                {{ $product->is_featured ? 'Bỏ Nổi Bật' : 'Đặt Nổi Bật' }}
                            </button>

                            <a href="{{ route('admin.products.create') }}"
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                                <i class="fas fa-plus mr-2"></i>
                                Thêm Sản Phẩm Mới
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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
    </script>
</x-admin-layout>
