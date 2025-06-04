<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Bánh Ngọt Tươi Ngon</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Styles -->
    <style>
        .hero-bg {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-10px);
        }
    </style>
</head>
<body class="antialiased">
<!-- Navigation -->
<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <h1 class="text-2xl font-bold text-pink-600">🧁 Sweet Delights</h1>
                </div>
                <div class="hidden md:ml-6 md:flex md:space-x-8">
                    <a href="#home" class="text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium border-b-2 border-pink-500">
                        Trang Chủ
                    </a>
                    <a href="#products" class="text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium transition duration-200">
                        Sản Phẩm
                    </a>
                    <a href="#categories" class="text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium transition duration-200">
                        Danh Mục
                    </a>
                    <a href="#about" class="text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium transition duration-200">
                        Về Chúng Tôi
                    </a>
                    <a href="#contact" class="text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium transition duration-200">
                        Liên Hệ
                    </a>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <!-- Cart Icon -->
                <button class="relative p-2 text-gray-600 hover:text-gray-900 transition duration-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01" />
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-pink-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                </button>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 hover:text-gray-900 transition duration-200">Dashboard</a>
                        @if(auth()->user()->email === 'admin@example.com')
                            <a href="{{ route('admin.dashboard') }}" class="text-sm text-pink-600 hover:text-pink-800 font-medium transition duration-200">Admin</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900 transition duration-200">Đăng Nhập</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 hover:text-gray-900 transition duration-200">Đăng Ký</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section id="home" class="hero-bg py-20 lg:py-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="text-center lg:text-left">
                <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6">
                    Bánh Ngọt <span class="text-pink-600">Tươi Ngon</span>
                    <br>Mỗi Ngày
                </h1>
                <p class="text-xl text-gray-700 mb-8 max-w-lg">
                    Khám phá những chiếc bánh ngọt được làm thủ công với tình yêu và nguyên liệu tươi ngon nhất.
                    Mỗi miếng bánh là một trải nghiệm tuyệt vời!
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('products.index') }}" class="bg-pink-600 text-white px-8 py-4 rounded-lg text-lg font-medium hover:bg-pink-700 transition duration-300 transform hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Mua Ngay
                    </a>
                    <a href="#products" class="bg-white text-pink-600 px-8 py-4 rounded-lg text-lg font-medium border-2 border-pink-600 hover:bg-pink-50 transition duration-300 shadow-lg">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Xem Sản Phẩm
                    </a>
                </div>
            </div>
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&h=600&fit=crop"
                     alt="Bánh ngọt tươi ngon"
                     class="rounded-3xl shadow-2xl transform rotate-3 hover:rotate-0 transition duration-500">
                <div class="absolute -top-4 -left-4 bg-yellow-400 text-yellow-900 px-4 py-2 rounded-full font-bold transform -rotate-12 shadow-lg">
                    🔥 Hot Sale!
                </div>
                <div class="absolute -bottom-4 -right-4 bg-pink-500 text-white px-4 py-2 rounded-full font-bold transform rotate-12 shadow-lg">
                    ⭐ Tươi ngon
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-4xl font-bold text-pink-600 mb-2">{{ \App\Models\Product::count() }}+</div>
                <div class="text-gray-600">Sản Phẩm</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-pink-600 mb-2">1000+</div>
                <div class="text-gray-600">Khách Hàng</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-pink-600 mb-2">{{ \App\Models\Category::count() }}+</div>
                <div class="text-gray-600">Danh Mục</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-pink-600 mb-2">5⭐</div>
                <div class="text-gray-600">Đánh Giá</div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section id="categories" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Danh Mục Sản Phẩm</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Khám phá các loại bánh ngọt đa dạng được yêu thích nhất</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @php
                $categories = \App\Models\Category::active()->withCount('products')->get();
            @endphp

            @forelse($categories as $category)
                <div class="text-center group cursor-pointer card-hover">
                    <div class="bg-white rounded-2xl p-6 shadow-lg group-hover:shadow-xl transition duration-300">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-pink-100 to-purple-100 rounded-full flex items-center justify-center group-hover:from-pink-200 group-hover:to-purple-200 transition duration-300">
                            @if($category->image_url)
                                <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-12 h-12 rounded-full object-cover">
                            @else
                                <span class="text-2xl">🧁</span>
                            @endif
                        </div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-pink-600 transition duration-300 mb-2">
                            {{ $category->name }}
                        </h3>
                        <p class="text-sm text-gray-500">{{ $category->products_count }} sản phẩm</p>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-500">Chưa có danh mục nào</p>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('products.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-pink-600 bg-pink-100 hover:bg-pink-200 transition duration-300">
                Xem Tất Cả Danh Mục
                <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section id="products" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Sản Phẩm Nổi Bật</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Những chiếc bánh ngọt được yêu thích và bán chạy nhất</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @php
                $featuredProducts = \App\Models\Product::with('category')->active()->featured()->take(8)->get();
            @endphp

            @forelse($featuredProducts as $product)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 card-hover group">
                    <a href="{{ route('products.show', $product) }}">
                        <div class="aspect-w-1 aspect-h-1 bg-gray-200 relative overflow-hidden">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-64 object-cover group-hover:scale-110 transition duration-500">
                            @else
                                <div class="w-full h-64 bg-gradient-to-br from-pink-100 to-purple-100 flex items-center justify-center">
                                    <span class="text-5xl">🧁</span>
                                </div>
                            @endif

                            <div class="absolute top-3 left-3">
                                    <span class="bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                                        ⭐ Nổi bật
                                    </span>
                            </div>

                            @if($product->stock_quantity <= 0)
                                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                    <span class="text-white font-bold text-lg">Hết hàng</span>
                                </div>
                            @endif
                        </div>
                    </a>

                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-medium text-pink-600 bg-pink-50 px-3 py-1 rounded-full">
                                    {{ $product->category->name ?? 'Khác' }}
                                </span>
                            @if($product->stock_quantity > 0)
                                <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full">Còn hàng</span>
                            @else
                                <span class="text-xs text-red-600 bg-red-50 px-2 py-1 rounded-full">Hết hàng</span>
                            @endif
                        </div>

                        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-1">
                            <a href="{{ route('products.show', $product) }}" class="hover:text-pink-600 transition duration-300">
                                {{ $product->name }}
                            </a>
                        </h3>

                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                            {{ Str::limit($product->description, 60) }}
                        </p>

                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-pink-600">{{ $product->formatted_price }}</span>
                            @if($product->stock_quantity > 0)
                                <button class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 transition duration-300 text-sm font-medium transform hover:scale-105">
                                    Thêm vào giỏ
                                </button>
                            @else
                                <button class="bg-gray-300 text-gray-500 px-4 py-2 rounded-lg cursor-not-allowed text-sm" disabled>
                                    Hết hàng
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-500">Chưa có sản phẩm nổi bật nào</p>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('products.index') }}" class="bg-gradient-to-r from-pink-600 to-purple-600 text-white px-8 py-4 rounded-lg text-lg font-medium hover:from-pink-700 hover:to-purple-700 transition duration-300 transform hover:scale-105 shadow-lg">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                Xem Tất Cả Sản Phẩm
            </a>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="order-2 lg:order-1">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Về Sweet Delights</h2>
                <p class="text-gray-600 mb-6 text-lg leading-relaxed">
                    Sweet Delights là tiệm bánh ngọt gia đình với hơn 10 năm kinh nghiệm trong việc tạo ra những chiếc bánh ngọt tươi ngon và đầy hương vị.
                </p>
                <p class="text-gray-600 mb-8 text-lg leading-relaxed">
                    Chúng tôi cam kết sử dụng những nguyên liệu tươi ngon nhất và công thức truyền thống được truyền qua nhiều thế hệ để mang đến cho bạn những trải nghiệm ẩm thực tuyệt vời nhất.
                </p>

                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="bg-green-100 rounded-full p-2 mr-4">
                            <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="text-gray-700 font-medium">Nguyên liệu tươi ngon 100%</span>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-green-100 rounded-full p-2 mr-4">
                            <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="text-gray-700 font-medium">Làm thủ công với tình yêu</span>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-green-100 rounded-full p-2 mr-4">
                            <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="text-gray-700 font-medium">Giao hàng nhanh chóng</span>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-green-100 rounded-full p-2 mr-4">
                            <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="text-gray-700 font-medium">Giá cả hợp lý</span>
                    </div>
                </div>
            </div>

            <div class="order-1 lg:order-2 grid grid-cols-2 gap-4">
                <img src="https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?w=300&h=400&fit=crop"
                     alt="Bánh ngọt"
                     class="rounded-2xl shadow-lg transform rotate-3 hover:rotate-0 transition duration-500">
                <img src="https://images.unsplash.com/photo-1488477181946-6428a0291777?w=300&h=400&fit=crop"
                     alt="Tiệm bánh"
                     class="rounded-2xl shadow-lg transform -rotate-3 hover:rotate-0 transition duration-500 mt-8">
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Khách Hàng Nói Gì</h2>
            <p class="text-xl text-gray-600">Những phản hồi tuyệt vời từ khách hàng của chúng tôi</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-gray-50 rounded-2xl p-8 card-hover">
                <div class="flex items-center mb-4">
                    <div class="flex text-yellow-400">
                        ⭐⭐⭐⭐⭐
                    </div>
                </div>
                <p class="text-gray-600 mb-6 italic">"Bánh ở đây thực sự tuyệt vời! Tôi đã thử nhiều loại và tất cả đều rất ngon. Đặc biệt là bánh tiramisu, không thể nào quên được."</p>
                <div class="flex items-center">
                    <img src="https://images.unsplash.com/photo-1494790108755-2616b612b5bc?w=64&h=64&fit=crop&crop=face"
                         alt="Nguyễn Thị Mai"
                         class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <h4 class="font-semibold text-gray-900">Nguyễn Thị Mai</h4>
                        <p class="text-gray-500 text-sm">Khách hàng thường xuyên</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-2xl p-8 card-hover">
                <div class="flex items-center mb-4">
                    <div class="flex text-yellow-400">
                        ⭐⭐⭐⭐⭐
                    </div>
                </div>
                <p class="text-gray-600 mb-6 italic">"Dịch vụ giao hàng rất nhanh và bánh vẫn giữ được độ tươi ngon. Tôi rất hài lòng với chất lượng sản phẩm tại đây."</p>
                <div class="flex items-center">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=64&h=64&fit=crop&crop=face"
                         alt="Trần Văn Nam"
                         class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <h4 class="font-semibold text-gray-900">Trần Văn Nam</h4>
                        <p class="text-gray-500 text-sm">Doanh nhân</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-2xl p-8 card-hover">
                <div class="flex items-center mb-4">
                    <div class="flex text-yellow-400">
                        ⭐⭐⭐⭐⭐
                    </div>
                </div>
                <p class="text-gray-600 mb-6 italic">"Tôi đã đặt bánh sinh nhật cho con và mọi người đều khen ngon lắm. Thiết kế bánh cũng rất đẹp mắt."</p>
                <div class="flex items-center">
                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=64&h=64&fit=crop&crop=face"
                         alt="Lê Thị Hoa"
                         class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <h4 class="font-semibold text-gray-900">Lê Thị Hoa</h4>
                        <p class="text-gray-500 text-sm">Mẹ bỉm sữa</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-20 bg-gradient-to-r from-pink-50 to-purple-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Liên Hệ Với Chúng Tôi</h2>
            <p class="text-xl text-gray-600">Chúng tôi luôn sẵn sàng phục vụ và lắng nghe bạn</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl p-8 text-center shadow-lg card-hover">
                <div class="bg-pink-100 rounded-full w-16 h-16 mx-auto mb-6 flex items-center justify-center">
                    <svg class="h-8 w-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-4 text-xl">Địa Chỉ</h3>
                <p class="text-gray-600 leading-relaxed">
                    123 Đường ABC, Phường XYZ<br>
                    Quận 1, TP. Hồ Chí Minh<br>
                    Việt Nam
                </p>
            </div>

            <div class="bg-white rounded-2xl p-8 text-center shadow-lg card-hover">
                <div class="bg-pink-100 rounded-full w-16 h-16 mx-auto mb-6 flex items-center justify-center">
                    <svg class="h-8 w-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-4 text-xl">Điện Thoại</h3>
                <p class="text-gray-600 leading-relaxed">
                    Hotline: <a href="tel:0123456789" class="text-pink-600 hover:text-pink-800 font-medium">0123 456 789</a><br>
                    Zalo: <a href="tel:0987654321" class="text-pink-600 hover:text-pink-800 font-medium">0987 654 321</a><br>
                    <span class="text-sm text-gray-500">Hoạt động 24/7</span>
                </p>
            </div>

            <div class="bg-white rounded-2xl p-8 text-center shadow-lg card-hover">
                <div class="bg-pink-100 rounded-full w-16 h-16 mx-auto mb-6 flex items-center justify-center">
                    <svg class="h-8 w-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-4 text-xl">Giờ Mở Cửa</h3>
                <p class="text-gray-600 leading-relaxed">
                    <strong>Thứ 2 - Thứ 7:</strong> 8:00 - 22:00<br>
                    <strong>Chủ Nhật:</strong> 9:00 - 21:00<br>
                    <span class="text-pink-600 font-medium">Giao hàng miễn phí từ 200k</span>
                </p>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="mt-16 max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Gửi Tin Nhắn</h3>
                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Họ và tên</label>
                            <input type="text" id="name" name="name" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition duration-200">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại</label>
                            <input type="tel" id="phone" name="phone" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition duration-200">
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition duration-200">
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Tin nhắn</label>
                        <textarea id="message" name="message" rows="4" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition duration-200"
                                  placeholder="Bạn muốn hỏi gì về sản phẩm của chúng tôi?"></textarea>
                    </div>
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-pink-600 to-purple-600 text-white py-4 px-6 rounded-lg font-medium hover:from-pink-700 hover:to-purple-700 transition duration-300 transform hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Gửi Tin Nhắn
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-16 bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Đăng Ký Nhận Tin</h2>
        <p class="text-gray-300 mb-8 max-w-2xl mx-auto">Đăng ký để nhận thông tin về sản phẩm mới, khuyến mãi đặc biệt và những mẹo làm bánh thú vị</p>

        <div class="max-w-md mx-auto">
            <form class="flex gap-4">
                <input type="email" placeholder="Nhập email của bạn" required
                       class="flex-1 px-4 py-3 rounded-lg border-0 focus:ring-2 focus:ring-pink-500 transition duration-200">
                <button type="submit"
                        class="bg-pink-600 text-white px-6 py-3 rounded-lg hover:bg-pink-700 transition duration-300 font-medium whitespace-nowrap">
                    Đăng Ký
                </button>
            </form>
            <p class="text-gray-400 text-sm mt-4">Chúng tôi tôn trọng quyền riêng tư của bạn</p>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4">🧁 Sweet Delights</h3>
                <p class="text-gray-400 mb-4">Bánh ngọt tươi ngon được làm với tình yêu và sự tận tâm từ năm 2014.</p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition duration-200">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-200">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-200">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.097.118.112.165.085.289-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div>
                <h4 class="font-semibold mb-4">Liên Kết Nhanh</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#home" class="hover:text-white transition duration-200">Trang Chủ</a></li>
                    <li><a href="{{ route('products.index') }}" class="hover:text-white transition duration-200">Sản Phẩm</a></li>
                    <li><a href="#categories" class="hover:text-white transition duration-200">Danh Mục</a></li>
                    <li><a href="#about" class="hover:text-white transition duration-200">Về Chúng Tôi</a></li>
                    <li><a href="#contact" class="hover:text-white transition duration-200">Liên Hệ</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold mb-4">Hỗ Trợ Khách Hàng</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white transition duration-200">Chính Sách Đổi Trả</a></li>
                    <li><a href="#" class="hover:text-white transition duration-200">Hướng Dẫn Đặt Hàng</a></li>
                    <li><a href="#" class="hover:text-white transition duration-200">Chính Sách Giao Hàng</a></li>
                    <li><a href="#" class="hover:text-white transition duration-200">Câu Hỏi Thường Gặp</a></li>
                    <li><a href="#" class="hover:text-white transition duration-200">Điều Khoản Sử Dụng</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold mb-4">Thông Tin Liên Hệ</h4>
                <div class="text-gray-400 space-y-3">
                    <p class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        123 Đường ABC, Quận 1<br>TP. Hồ Chí Minh
                    </p>
                    <p class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        0123 456 789
                    </p>
                    <p class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        info@sweetdelights.com
                    </p>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; 2024 Sweet Delights. Tất cả quyền được bảo lưu. Thiết kế bởi Sweet Delights Team.</p>
        </div>
    </div>
</footer>

<script>
    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add to cart functionality (placeholder)
    document.querySelectorAll('button').forEach(button => {
        if (button.textContent.includes('Thêm vào giỏ')) {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                // Animate button
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);

                // Show notification
                showNotification('Sản phẩm đã được thêm vào giỏ hàng! 🛒');
            });
        }
    });

    // Contact form submission
    document.querySelector('#contact form').addEventListener('submit', function(e) {
        e.preventDefault();

        // Get form data
        const formData = new FormData(this);
        const name = formData.get('name');

        // Show success message
        showNotification(`Cảm ơn ${name}! Chúng tôi sẽ liên hệ với bạn sớm nhất có thể. 📞`);

        // Reset form
        this.reset();
    });

    // Newsletter form submission
    document.querySelector('section:nth-last-of-type(2) form').addEventListener('submit', function(e) {
        e.preventDefault();

        // Show success message
        showNotification('Đăng ký thành công! Bạn sẽ nhận được tin tức mới nhất từ chúng tôi. 📧');

        // Reset form
        this.reset();
    });

    // Simple notification function
    function showNotification(message) {
        // Remove existing notification
        const existing = document.querySelector('.notification');
        if (existing) {
            existing.remove();
        }

        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'notification fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300 max-w-sm';
        notification.innerHTML = `
                <div class="flex items-center">
                    <span class="flex-1">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;

        document.body.appendChild(notification);

        // Show notification
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);

        // Hide notification after 5 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }, 5000);
    }

    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const nav = document.querySelector('nav');
        if (window.scrollY > 100) {
            nav.classList.add('bg-white/95', 'backdrop-blur-sm');
        } else {
            nav.classList.remove('bg-white/95', 'backdrop-blur-sm');
        }
    });

    // Lazy loading for images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('opacity-0');
                    img.classList.add('opacity-100');
                    observer.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
</script>
</body>
</html>
