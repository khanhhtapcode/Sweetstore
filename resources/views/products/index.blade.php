<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Thêm CSRF token -->
    <title>Sản Phẩm - Sweet Delights</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/7717218_dessert_cake_mothers_day_mom_icon.ico') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Cart Pop-up Overlay Styles */
        #cartOverlay {
            display: none;
            position: fixed;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: flex-end;
            align-items: flex-start;
            overflow-y: auto;
            padding: 20px;
        }

        #cartContent {
            background-color: white;
            border-radius: 12px;
            width: 100%;
            max-width: 400px;
            max-height: 100vh;
            overflow-y: auto;
            position: relative;
            box-shadow: -10px 0 25px rgba(0, 0, 0, 0.3);
            margin-right: 0;
        }

        #closeCart {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
            transition: color 0.3s ease;
        }

        #closeCart:hover {
            color: #dc2626;
        }

        /* Container giỏ hàng trong pop-up */
        .cart-container {
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        /* Thông báo giỏ hàng trống */
        .cart-empty {
            text-align: center;
            padding: 40px 20px;
            background-color: #f9fafb;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .cart-empty p {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #555;
        }

        .cart-empty a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2563eb;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .cart-empty a:hover {
            background-color: #1e40af;
        }

        /* Cart Items */
        .cart-items {
            max-height: 60vh;
            overflow-y: auto;
        }

        .cart-item {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        /* Tổng tiền */
        .cart-total {
            margin-top: 20px;
            text-align: right;
            font-size: 1.3rem;
            font-weight: 700;
            color: #111827;
        }

        /* Cart Actions */
        .cart-actions {
            display: flex;
            gap: 16px;
        }

        /* Responsive: trên màn hình nhỏ */
        @media (max-width: 600px) {
            #cartContent {
                max-width: 100%;
            }
        }
    </style>
</head>

<body class="antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-25">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="{{ url('/') }}" class="text-2xl font-bold text-pink-600">
                            <img src="{{ asset('images/sweet-delights-logo.svg') }}" alt="Sweet Delights Logo" class="h-20 w-auto">
                        </a>
                    </div>
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        <a href="{{ url('/') }}" class="text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium transition duration-200">
                            Trang Chủ
                        </a>
                        <a href="{{ route('products.index') }}" class="text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium border-b-2 border-pink-500">
                            Sản Phẩm
                        </a>
                        <a href="{{ url('/#contact') }}" class="text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium transition duration-200">
                            Liên Hệ
                        </a>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Cart Icon -->
                    <button onclick="openCartOverlay()" class="relative p-2 text-gray-600 hover:text-gray-900 transition duration-200">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01" />
                        </svg>
                        <span class="absolute top-1 -right-1 bg-pink-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center cart-count">
                            {{ auth()->check() ? \App\Models\CartItem::where('user_id', auth()->id())->count() : 0 }}
                        </span>
                    </button>

                    @auth
                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 hover:text-gray-900 transition duration-200">Dashboard</a>
                    @if(auth()->user()->email === 'admin@example.com')
                    <a href="{{ route('admin.dashboard') }}" class="text-sm text-pink-600 hover:text-pink-800 font-medium transition duration-200">Admin</a>
                    @endif
                    @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900 transition duration-200">Đăng Nhập</a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 hover:text-gray-900 transition duration-200">Đăng Ký</a>
                    @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="bg-gradient-to-r from-pink-50 to-purple-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Tất Cả Sản Phẩm</h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Khám phá bộ sưu tập bánh ngọt tuyệt vời của chúng tôi với {{ $products->total() }} sản phẩm đa dạng</p>
            </div>
        </div>
    </div>

    <!-- Cart Pop-up Overlay -->
    <div id="cartOverlay">
        <div id="cartContent">
            @php
            $cartItems = auth()->check() ? \App\Models\CartItem::where('user_id', auth()->id())->get() : collect([]);
            $totalPrice = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
            });
            @endphp
            @include('pages.cart.overlay', ['cartItems' => $cartItems, 'totalPrice' => $totalPrice])
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <div class="lg:w-1/4">
                <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                        </svg>
                        Bộ Lọc
                    </h3>

                    <form method="GET" action="{{ route('products.index') }}" class="space-y-6">
                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Tìm Kiếm
                            </label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Tên sản phẩm..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition duration-200">
                        </div>

                        <!-- Categories -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                Danh Mục
                            </label>
                            <div class="space-y-2 max-h-48 overflow-y-auto">
                                <label class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition duration-200">
                                    <input type="radio" name="category" value="" {{ !request('category') ? 'checked' : '' }} class="text-pink-600 focus:ring-pink-500">
                                    <span class="ml-3 text-sm text-gray-700 font-medium">Tất cả</span>
                                    <span class="ml-auto text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ $products->total() }}</span>
                                </label>
                                @foreach($categories as $category)
                                <label class="flex items-center p-2 rounded-lg hover:bg-gray-50 transition duration-200">
                                    <input type="radio" name="category" value="{{ $category->id }}" {{ request('category') == $category->id ? 'checked' : '' }} class="text-pink-600 focus:ring-pink-500">
                                    <span class="ml-3 text-sm text-gray-700">{{ $category->name }}</span>
                                    <span class="ml-auto text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ $category->products_count }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Sort -->
                        <div>
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                                </svg>
                                Sắp Xếp
                            </label>
                            <select name="sort" id="sort" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition duration-200">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Nổi bật</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Giá thấp đến cao</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Giá cao đến thấp</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-pink-600 to-purple-600 text-white py-3 px-4 rounded-lg hover:from-pink-700 hover:to-purple-700 transition duration-300 font-medium transform hover:scale-105">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                            </svg>
                            Áp Dụng Bộ Lọc
                        </button>

                        @if(request()->hasAny(['search', 'category', 'sort']))
                        <a href="{{ route('products.index') }}" class="w-full block text-center bg-gray-100 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-200 transition duration-200">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Xóa Bộ Lọc
                        </a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="lg:w-3/4">
                <!-- Results Info -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 bg-white rounded-lg shadow-sm p-4">
                    <div class="mb-4 sm:mb-0">
                        <p class="text-gray-600">
                            Hiển thị <span class="font-semibold text-gray-900">{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</span>
                            trong <span class="font-semibold text-gray-900">{{ $products->total() }}</span> sản phẩm
                        </p>
                        @if(request('search'))
                        <p class="text-sm text-gray-500 mt-1">
                            Kết quả tìm kiếm cho: "<span class="font-medium">{{ request('search') }}</span>"
                        </p>
                        @endif
                    </div>

                    <!-- Quick Sort -->
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-600">Xem nhanh:</span>
                        <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'featured'])) }}" class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full hover:bg-yellow-200 transition duration-200">
                            ⭐ Nổi bật
                        </a>
                        <a href="{{ route('products.index', array_merge(request()->all(), ['sort' => 'price_low'])) }}" class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full hover:bg-green-200 transition duration-200">
                            💰 Giá rẻ
                        </a>
                    </div>
                </div>

                @if($products->count() > 0)
                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($products as $product)
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-1 group">
                        <a href="{{ route('products.show', $product) }}">
                            <div class="aspect-w-1 aspect-h-1 bg-gray-200 relative overflow-hidden">
                                @if($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-56 object-cover group-hover:scale-110 transition duration-500">
                                @else
                                <div class="w-full h-56 bg-gradient-to-br from-pink-100 to-purple-100 flex items-center justify-center">
                                    <span class="text-5xl">🧁</span>
                                </div>
                                @endif

                                <!-- Badges -->
                                <div class="absolute top-3 left-3 flex flex-col space-y-2">
                                    @if($product->is_featured)
                                    <span class="bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded-full shadow-sm">
                                        ⭐ Nổi bật
                                    </span>
                                    @endif
                                    @if($product->stock_quantity <= 5 && $product->stock_quantity > 0)
                                        <span class="bg-orange-400 text-orange-900 text-xs font-bold px-2 py-1 rounded-full shadow-sm">
                                            🔥 Sắp hết
                                        </span>
                                        @endif
                                </div>

                                @if($product->stock_quantity <= 0)
                                    <div class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center">
                                    <div class="text-white font-bold text-center">
                                        <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364L18.364 5.636"></path>
                                        </svg>
                                        <span class="text-lg">Hết hàng</span>
                                    </div>
                            </div>
                            @endif

                            <!-- Quick View -->
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition duration-300 flex items-center justify-center">
                                <button class="bg-white text-gray-900 px-4 py-2 rounded-lg opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition duration-300 font-medium shadow-lg">
                                    Xem Chi Tiết
                                </button>
                            </div>
                    </div>
                    </a>

                    <div class="p-5">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-medium text-pink-600 bg-pink-50 px-3 py-1 rounded-full">
                                {{ $product->category->name ?? 'Khác' }}
                            </span>
                            @if($product->stock_quantity > 0)
                            <span class="text-xs text-green-500 bg-green-50 px-2 py-1 rounded">
                                Còn {{ $product->stock_quantity }}
                            </span>
                            @else
                            <span class="text-xs text-red-600 bg-red-50 px-2 py-1 rounded-full">Hết hàng</span>
                            @endif
                        </div>

                        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-1">
                            <a href="{{ route('products.show', $product) }}" class="hover:text-pink-600 transition duration-200">
                                {{ $product->name }}
                            </a>
                        </h3>

                        <p class="text-sm text-gray-600 mb-4 line-clamp-2 leading-relaxed">
                            {{ Str::limit($product->description, 80) }}
                        </p>

                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-2xl font-bold text-pink-600">{{ $product->formatted_price }}</span>
                                @if($product->category)
                                <span class="text-xs text-gray-500">{{ $product->category->name }}</span>
                                @endif
                            </div>
                            @if($product->stock_quantity > 0)
                            @if(auth()->check())
                            <form action="{{ route('cart.add') }}" method="POST" class="cart-form" data-product-id="{{ $product->id }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="add-to-cart bg-gradient-to-r from-pink-600 to-purple-600 text-white px-5 py-2.5 rounded-lg hover:from-pink-700 hover:to-purple-700 transition duration-300 font-medium transform hover:scale-105 shadow-md" data-loading-text="...">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                                    </svg>
                                    Thêm
                                </button>
                            </form>
                            @else
                            <button onclick="showLoginPrompt()" class="bg-gradient-to-r from-pink-600 to-purple-600 text-white px-5 py-2.5 rounded-lg hover:from-pink-700 hover:to-purple-700 transition duration-300 font-medium transform hover:scale-105 shadow-md">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                                </svg>
                                Thêm
                            </button>
                            @endif
                            @else
                            <button class="bg-gray-300 text-gray-500 px-5 py-2.5 rounded-lg cursor-not-allowed font-medium" disabled>
                                Hết hàng
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
            <div class="flex justify-center">
                <div class="bg-white rounded-lg shadow-sm p-4">
                    {{ $products->withQueryString()->links() }}
                </div>
            </div>
            @endif
            @else
            <!-- No Products -->
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <div class="max-w-md mx-auto">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10l-8-4m8 4v10M4 7v10l8 4" />
                    </svg>
                    <h3 class="mt-2 text-xl font-medium text-gray-900">Không tìm thấy sản phẩm nào</h3>
                    <p class="mt-2 text-gray-500">Thử thay đổi bộ lọc hoặc tìm kiếm với từ khóa khác để tìm sản phẩm phù hợp.</p>
                    <div class="mt-6 space-y-3">
                        <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 transition duration-300">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Xem tất cả sản phẩm
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    </div>

    <!-- JavaScript -->
    <script>
        // --- Show notification ---
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg text-white z-50 transition-all duration-300 transform translate-x-full ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => notification.classList.remove('translate-x-full'), 100);
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // --- Handle cart action with debounce prevention ---
        let isCartActionRunning = false;

        async function handleCartAction(element, event) {
            event.preventDefault();
            if (isCartActionRunning) return;
            isCartActionRunning = true;

            let url, method, formData;

            if (element.classList.contains('add-to-cart')) {
                const form = element.closest('form');
                if (!form) return showNotification('Không tìm thấy form!', 'error');
                url = '{{ route("cart.add") }}';
                method = 'POST';
                formData = new FormData(form);
                if (!formData.get('quantity')) formData.set('quantity', 1);
            } else if (element.closest('form')?.classList.contains('cart-form')) {
                url = '{{ route("cart.update") }}';
                method = 'POST';
                formData = new FormData(element.closest('form'));
            } else if (element.classList.contains('delete-btn')) {
                const productId = element.getAttribute('data-product-id');
                if (!productId) return showNotification('Không tìm thấy ID sản phẩm!', 'error');
                url = '{{ route("cart.delete", ["productId" => ":id"]) }}'.replace(':id', productId);
                method = 'POST';
                formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            } else {
                return showNotification('Hành động không hợp lệ!', 'error');
            }

            element.disabled = true;
            const originalText = element.textContent;
            if (element.classList.contains('add-to-cart')) element.textContent = element.dataset.loadingText || '...';

            try {
                const response = await fetch(url, {
                    method,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                const data = await response.json();

                if (!response.ok) throw new Error(data.message || 'Unknown error');

                document.getElementById('cartContent').innerHTML = data.cartHtml;
                document.querySelector('.relative span').textContent = data.cartCount;
                attachCartEvents();
                showNotification(data.message || 'Cập nhật giỏ hàng thành công! 🛒');
                if (element.classList.contains('add-to-cart')) openCartOverlay();
            } catch (error) {
                console.error('Fetch error:', error);
                showNotification('Có lỗi xảy ra: ' + error.message, 'error');
            } finally {
                isCartActionRunning = false;
                element.disabled = false;
                if (element.classList.contains('add-to-cart')) element.textContent = originalText;
            }
        }

        // --- Attach cart events ---
        function attachCartEvents() {
            document.querySelectorAll('.add-to-cart, .cart-form button, .delete-btn').forEach(button => {
                button.replaceWith(button.cloneNode(true)); // Remove old listeners
            });

            document.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', e => handleCartAction(button, e));
            });

            document.querySelectorAll('.cart-form button').forEach(button => {
                button.addEventListener('click', e => handleCartAction(button, e));
            });

            document.querySelectorAll('.delete-btn').forEach(link => {
                link.addEventListener('click', e => handleCartAction(link, e));
            });

            const closeCartBtn = document.getElementById('closeCart');
            if (closeCartBtn) closeCartBtn.onclick = closeCartOverlay;
        }

        // --- Overlay ---
        function openCartOverlay() {
            const overlay = document.getElementById('cartOverlay');
            overlay.style.display = 'flex';
            setTimeout(() => overlay.classList.add('opacity-100'), 10);
            document.body.style.overflow = 'hidden';
        }

        function closeCartOverlay() {
            const overlay = document.getElementById('cartOverlay');
            overlay.classList.remove('opacity-100');
            setTimeout(() => {
                overlay.style.display = 'none';
                document.body.style.overflow = '';
            }, 300);
        }

        function showLoginPrompt() {
            showNotification('Vui lòng đăng nhập để thực hiện hành động này!', 'error');
            setTimeout(() => window.location.href = '{{ route("login") }}', 2000);
        }

        // --- Auto submit filters ---
        document.querySelectorAll('input[name="category"]').forEach(input => {
            input.addEventListener('change', () => input.closest('form').submit());
        });

        document.getElementById('sort')?.addEventListener('change', function() {
            this.closest('form').submit();
        });

        // --- Smooth scroll ---
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        });

        // --- On load ---
        document.addEventListener('DOMContentLoaded', attachCartEvents);

        // --- Loading animation for submit ---
        document.querySelector('form')?.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg> Đang tải...`;
                submitBtn.disabled = true;
            }
        });
    </script>
</body>

</html>