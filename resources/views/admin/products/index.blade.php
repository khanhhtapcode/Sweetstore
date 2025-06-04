<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sản Phẩm - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50">
<!-- Navigation -->
<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <a href="{{ url('/') }}" class="text-2xl font-bold text-pink-600">🧁 Sweet Delights</a>
                </div>
                <div class="hidden md:ml-6 md:flex md:space-x-8">
                    <a href="{{ url('/') }}" class="text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium">
                        Trang Chủ
                    </a>
                    <a href="{{ route('products.index') }}" class="text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium border-b-2 border-pink-500">
                        Sản Phẩm
                    </a>
                    <a href="{{ url('/#contact') }}" class="text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium">
                        Liên Hệ
                    </a>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <!-- Cart Icon -->
                <button class="relative p-2 text-gray-600 hover:text-gray-900">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01" />
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-pink-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                </button>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 underline">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Đăng Nhập</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Đăng Ký</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>
</nav>

<!-- Page Header -->
<div class="bg-gradient-to-r from-pink-50 to-purple-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Tất Cả Sản Phẩm</h1>
            <p class="text-xl text-gray-600">Khám phá bộ sưu tập bánh ngọt tuyệt vời của chúng tôi</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filters -->
        <div class="lg:w-1/4">
            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-24">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Bộ Lọc</h3>

                <form method="GET" action="{{ route('products.index') }}" class="space-y-6">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Tìm Kiếm</label>
                        <input type="text"
                               name="search"
                               id="search"
                               value="{{ request('search') }}"
                               placeholder="Tên sản phẩm..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                    </div>

                    <!-- Categories -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Danh Mục</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio"
                                       name="category"
                                       value=""
                                       {{ !request('category') ? 'checked' : '' }}
                                       class="text-pink-600 focus:ring-pink-500">
                                <span class="ml-2 text-sm text-gray-700">Tất cả</span>
                            </label>
                            @foreach($categories as $category)
                                <label class="flex items-center">
                                    <input type="radio"
                                           name="category"
                                           value="{{ $category->id }}"
                                           {{ request('category') == $category->id ? 'checked' : '' }}
                                           class="text-pink-600 focus:ring-pink-500">
                                    <span class="ml-2 text-sm text-gray-700">
                                            {{ $category->name }} ({{ $category->products_count }})
                                        </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">Sắp Xếp</label>
                        <select name="sort"
                                id="sort"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Nổi bật</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Giá thấp đến cao</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Giá cao đến thấp</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-pink-600 text-white py-2 px-4 rounded-md hover:bg-pink-700 transition duration-300">
                        Áp Dụng Bộ Lọc
                    </button>
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="lg:w-3/4">
            <!-- Results Info -->
            <div class="flex justify-between items-center mb-6">
                <p class="text-gray-600">
                    Hiển thị {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}
                    trong {{ $products->total() }} sản phẩm
                </p>
            </div>

            @if($products->count() > 0)
                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($products as $product)
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition duration-300">
                            <a href="{{ route('products.show', $product) }}">
                                <div class="aspect-w-1 aspect-h-1 bg-gray-200 relative">
                                    @if($product->image_url)
                                        <img src="{{ $product->image_url }}"
                                             alt="{{ $product->name }}"
                                             class="w-full h-48 object-cover hover:scale-105 transition duration-300">
                                    @else
                                        <div class="w-full h-48 bg-gradient-to-br from-pink-100 to-purple-100 flex items-center justify-center">
                                            <span class="text-4xl">🧁</span>
                                        </div>
                                    @endif

                                    @if($product->is_featured)
                                        <div class="absolute top-2 left-2">
                                                <span class="bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded">
                                                    Nổi bật
                                                </span>
                                        </div>
                                    @endif

                                    @if($product->stock_quantity <= 0)
                                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                            <span class="text-white font-bold text-lg">Hết hàng</span>
                                        </div>
                                    @endif
                                </div>
                            </a>

                            <div class="p-4">
                                <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-medium text-pink-600 bg-pink-100 px-2 py-1 rounded">
                                            {{ $product->category->name ?? 'Khác' }}
                                        </span>
                                    @if($product->stock_quantity > 0)
                                        <span class="text-xs text-green-600">Còn {{ $product->stock_quantity }} sản phẩm</span>
                                    @else
                                        <span class="text-xs text-red-600">Hết hàng</span>
                                    @endif
                                </div>

                                <h3 class="font-semibold text-gray-900 mb-2">
                                    <a href="{{ route('products.show', $product) }}" class="hover:text-pink-600 transition duration-300">
                                        {{ $product->name }}
                                    </a>
                                </h3>

                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                    {{ Str::limit($product->description, 80) }}
                                </p>

                                <div class="flex items-center justify-between">
                                        <span class="text-lg font-bold text-pink-600">
                                            {{ $product->formatted_price }}
                                        </span>
                                    @if($product->stock_quantity > 0)
                                        <button class="bg-pink-600 text-white px-4 py-2 rounded hover:bg-pink-700 transition duration-300 text-sm">
                                            Thêm vào giỏ
                                        </button>
                                    @else
                                        <button class="bg-gray-400 text-white px-4 py-2 rounded cursor-not-allowed text-sm" disabled>
                                            Hết hàng
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $products->withQueryString()->links() }}
                </div>
            @else
                <!-- No Products -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">Không tìm thấy sản phẩm</h3>
                    <p class="mt-1 text-gray-500">Thử thay đổi bộ lọc hoặc tìm kiếm với từ khóa khác.</p>
                    <div class="mt-6">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700">
                            Xem tất cả sản phẩm
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-12 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4">🧁 Sweet Delights</h3>
                <p class="text-gray-400">Bánh ngọt tươi ngon được làm với tình yêu và sự tận tâm.</p>
            </div>

            <div>
                <h4 class="font-semibold mb-4">Liên Kết</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="{{ url('/') }}" class="hover:text-white">Trang Chủ</a></li>
                    <li><a href="{{ route('products.index') }}" class="hover:text-white">Sản Phẩm</a></li>
                    <li><a href="{{ url('/#contact') }}" class="hover:text-white">Liên Hệ</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold mb-4">Hỗ Trợ</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white">Chính Sách Đổi Trả</a></li>
                    <li><a href="#" class="hover:text-white">Hướng Dẫn Đặt Hàng</a></li>
                    <li><a href="#" class="hover:text-white">Câu Hỏi Thường Gặp</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold mb-4">Liên Hệ</h4>
                <div class="text-gray-400 space-y-2">
                    <p>📍 123 Đường ABC, Quận 1</p>
                    <p>📞 0123 456 789</p>
                    <p>✉️ info@sweetdelights.com</p>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; 2024 Sweet Delights. Tất cả quyền được bảo lưu.</p>
        </div>
    </div>
</footer>
</body>
</html>
