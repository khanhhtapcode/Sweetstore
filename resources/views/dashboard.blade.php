<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">
                                Xin chào, {{ Auth::user()->name }}! 👋
                            </h3>
                            <p class="text-gray-600 mt-2">
                                Chào mừng bạn đến với Sweet Delights.
                                @if(Auth::user()->isAdmin())
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 ml-2">
                                        👑 Quản trị viên
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                        👤 Khách hàng
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Hôm nay là</p>
                            <p class="text-lg font-semibold text-gray-900">{{ now()->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if(Auth::user()->isAdmin())
                <!-- Admin Quick Actions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Thao Tác Nhanh - Admin</h4>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <a href="{{ route('admin.dashboard') }}"
                               class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition duration-300">
                                <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <div>
                                    <h5 class="font-semibold text-gray-900">Admin Panel</h5>
                                    <p class="text-sm text-gray-600">Quản lý hệ thống</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.products.index') }}"
                               class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition duration-300">
                                <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <div>
                                    <h5 class="font-semibold text-gray-900">Sản Phẩm</h5>
                                    <p class="text-sm text-gray-600">{{ \App\Models\Product::count() }} sản phẩm</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.orders.index') }}"
                               class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition duration-300">
                                <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <div>
                                    <h5 class="font-semibold text-gray-900">Đơn Hàng</h5>
                                    <p class="text-sm text-gray-600">{{ \App\Models\Order::count() }} đơn hàng</p>
                                </div>
                            </a>

                            <a href="{{ route('admin.users.index') }}"
                               class="flex items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition duration-300">
                                <svg class="w-8 h-8 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                <div>
                                    <h5 class="font-semibold text-gray-900">Người Dùng</h5>
                                    <p class="text-sm text-gray-600">{{ \App\Models\User::count() }} người dùng</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- User Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Khám Phá Sweet Delights</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('products.index') }}"
                           class="flex items-center p-4 bg-pink-50 rounded-lg hover:bg-pink-100 transition duration-300 group">
                            <div class="bg-pink-100 rounded-full p-3 mr-4 group-hover:bg-pink-200 transition duration-300">
                                <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-semibold text-gray-900">Mua Sắm</h5>
                                <p class="text-sm text-gray-600">Khám phá sản phẩm mới</p>
                            </div>
                        </a>

                        <a href="{{ url('/#categories') }}"
                           class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition duration-300 group">
                            <div class="bg-purple-100 rounded-full p-3 mr-4 group-hover:bg-purple-200 transition duration-300">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-semibold text-gray-900">Danh Mục</h5>
                                <p class="text-sm text-gray-600">Xem theo loại bánh</p>
                            </div>
                        </a>

                        <a href="{{ url('/#contact') }}"
                           class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition duration-300 group">
                            <div class="bg-green-100 rounded-full p-3 mr-4 group-hover:bg-green-200 transition duration-300">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-semibold text-gray-900">Liên Hệ</h5>
                                <p class="text-sm text-gray-600">Hỗ trợ khách hàng</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- User Orders (if any) -->
            @if(Auth::user()->orders()->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Đơn Hàng Gần Đây</h4>
                        <div class="space-y-3">
                            @foreach(Auth::user()->orders()->latest()->take(3)->get() as $order)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $order->order_number }}</p>
                                        <p class="text-sm text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900">{{ $order->formatted_total }}</p>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status == 'delivered') bg-green-100 text-green-800
                                            @else bg-blue-100 text-blue-800
                                            @endif">
                                            {{ $order->status_name }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Featured Products -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-lg font-semibold text-gray-900">Sản Phẩm Nổi Bật</h4>
                        <a href="{{ route('products.index') }}" class="text-pink-600 hover:text-pink-800 text-sm font-medium">
                            Xem tất cả →
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @foreach(\App\Models\Product::with('category')->active()->featured()->take(4)->get() as $product)
                            <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition duration-300">
                                <a href="{{ route('products.show', $product) }}">
                                    @if($product->image_url)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-32 object-cover">
                                    @else
                                        <div class="w-full h-32 bg-gradient-to-br from-pink-100 to-purple-100 flex items-center justify-center">
                                            <span class="text-3xl">🧁</span>
                                        </div>
                                    @endif
                                </a>
                                <div class="p-3">
                                    <h5 class="font-semibold text-gray-900 text-sm line-clamp-1">{{ $product->name }}</h5>
                                    <p class="text-xs text-gray-600 mt-1">{{ $product->category->name ?? 'Khác' }}</p>
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="font-bold text-pink-600 text-sm">{{ $product->formatted_price }}</span>
                                        <a href="{{ route('products.show', $product) }}"
                                           class="bg-pink-600 text-white px-2 py-1 rounded text-xs hover:bg-pink-700 transition duration-300">
                                            Xem
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Account Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Thông Tin Tài Khoản</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tên</dt>
                                    <dd class="text-sm text-gray-900">{{ Auth::user()->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="text-sm text-gray-900">{{ Auth::user()->email }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Vai trò</dt>
                                    <dd class="text-sm text-gray-900">{{ Auth::user()->role_name }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Ngày tham gia</dt>
                                    <dd class="text-sm text-gray-900">{{ Auth::user()->created_at->format('d/m/Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Đăng nhập lần cuối</dt>
                                    <dd class="text-sm text-gray-900">
                                        {{ Auth::user()->last_login_at ? Auth::user()->last_login_at->format('d/m/Y H:i') : 'Chưa có thông tin' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tổng đơn hàng</dt>
                                    <dd class="text-sm text-gray-900">{{ Auth::user()->orders()->count() }} đơn hàng</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <a href="{{ route('profile.edit') }}"
                           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
                            Chỉnh Sửa Thông Tin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
