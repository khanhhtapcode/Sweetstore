
<x-admin-layout>
    <x-slot name="header">
        Admin Dashboard - Sweet Delights 🧁
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Categories Card -->
        <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
            <div class="flex items-center">
                <div class="text-blue-600 text-3xl mr-4">📁</div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Danh Mục</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ \App\Models\Category::count() }}</p>
                    <a href="{{ route('admin.categories.index') }}" class="text-blue-500 hover:underline text-sm">
                        Quản lý danh mục →
                    </a>
                </div>
            </div>
        </div>

        <!-- Products Card -->
        <div class="bg-green-50 rounded-lg p-6 border border-green-200">
            <div class="flex items-center">
                <div class="text-green-600 text-3xl mr-4">🧁</div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Sản Phẩm</h3>
                    <p class="text-2xl font-bold text-green-600">{{ \App\Models\Product::count() }}</p>
                    <a href="{{ route('admin.products.index') }}" class="text-green-500 hover:underline text-sm">
                        Quản lý sản phẩm →
                    </a>
                </div>
            </div>
        </div>

        <!-- Orders Card -->
        <div class="bg-purple-50 rounded-lg p-6 border border-purple-200">
            <div class="flex items-center">
                <div class="text-purple-600 text-3xl mr-4">📋</div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Đơn Hàng</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ \App\Models\Order::count() }}</p>
                    <a href="{{ route('admin.orders.index') }}" class="text-purple-500 hover:underline text-sm">
                        Quản lý đơn hàng →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Thao Tác Nhanh</h3>
        <div class="flex space-x-4">
            <a href="{{ route('admin.categories.create') }}"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Thêm Danh Mục
            </a>
            <a href="{{ route('admin.products.create') }}"
               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                + Thêm Sản Phẩm
            </a>
            <a href="{{ route('products.index') }}"
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                👁️ Xem Website
            </a>
        </div>
    </div>

    <!-- Recent Products -->
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Sản Phẩm Mới Nhất</h3>
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản Phẩm</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh Mục</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tồn Kho</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach(\App\Models\Product::with('category')->latest()->take(5)->get() as $product)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $product->category->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $product->formatted_price }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $product->stock_quantity <= 5 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $product->stock_quantity }}
                                </span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
