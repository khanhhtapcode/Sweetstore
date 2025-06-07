<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Bánh Ngọt Tươi Ngon</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/7717218_dessert_cake_mothers_day_mom_icon.ico') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Styles -->
    <style>
        /* Custom Styles */
        .hero-bg {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-10px);
        }

        /* Navigation link styles for smooth underline effect */
        .nav-link {
            position: relative;
            overflow: hidden;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #ec4899;
            transition: width 0.3s ease-in-out;
        }

        .nav-link.active::after {
            width: 100%;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        /* New styles for horizontal scroll */
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        #productScrollContainer {
            display: flex;
            gap: 8px;
            padding-bottom: 16px;
            scroll-behavior: smooth;
        }

        #productScrollContainer .card-hover {
            flex: 0 0 auto;
            width: 280px;
        }

        .draggable {
            user-select: none;
        }

        #scrollLeft,
        #scrollRight {
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        #scrollLeft:hover,
        #scrollRight:hover {
            opacity: 1;
        }

        @media (max-width: 640px) {

            #scrollLeft,
            #scrollRight {
                display: none;
            }
        }

        /* Floating Contact Icons */
        .floating-contact {
            position: fixed;
            right: 20px;
            bottom: 20px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .contact-icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .contact-icon:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
        }

        .contact-icon svg {
            width: 24px;
            height: 24px;
            z-index: 2;
        }

        /* Phone icon */
        .phone-icon {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
        }

        .phone-icon:hover {
            background: linear-gradient(135deg, #ff5252, #d32f2f);
        }

        /* Zalo icon */
        .zalo-icon {
            background: linear-gradient(135deg, #0084ff, #0068cc);
        }

        .zalo-icon:hover {
            background: linear-gradient(135deg, #0068cc, #004499);
        }

        /* Email icon */
        .email-icon {
            background: linear-gradient(135deg, #00bcd4, #0097a7);
        }

        .email-icon:hover {
            background: linear-gradient(135deg, #0097a7, #006064);
        }

        /* Facebook icon */
        .facebook-icon {
            background: linear-gradient(135deg, #1877f2, #166fe5);
        }

        .facebook-icon:hover {
            background: linear-gradient(135deg, #166fe5, #1565c0);
        }

        /* Scroll to top icon */
        .scroll-top-icon {
            background: linear-gradient(135deg, #9c27b0, #7b1fa2);
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .scroll-top-icon.show {
            opacity: 1;
            visibility: visible;
        }

        .scroll-top-icon:hover {
            background: linear-gradient(135deg, #7b1fa2, #4a148c);
        }

        /* Tooltip */
        .contact-icon::before {
            content: attr(data-tooltip);
            position: absolute;
            right: 70px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .contact-icon::after {
            content: '';
            position: absolute;
            right: 58px;
            top: 50%;
            transform: translateY(-50%);
            border: 6px solid transparent;
            border-left-color: rgba(0, 0, 0, 0.8);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .contact-icon:hover::before,
        .contact-icon:hover::after {
            opacity: 1;
            visibility: visible;
        }

        .contact-icon:hover::before {
            right: 80px;
        }

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
        /* Chatbot Styles */
        .chatbot-icon {
            position: fixed;
            left: 20px;
            bottom: 20px;
            z-index: 1000;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .chatbot-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.25);
        }

        .chatbot-icon svg {
            width: 28px;
            height: 28px;
            color: white;
        }

        .chatbot-container {
            position: fixed;
            left: 20px;
            bottom: 90px;
            z-index: 1001;
            width: 350px;
            height: 500px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            display: none;
            flex-direction: column;
            overflow: hidden;
        }

        .chatbot-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .chatbot-close {
            position: absolute;
            right: 15px;
            top: 15px;
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            padding: 5px;
            border-radius: 50%;
            transition: background 0.3s;
        }

        .chatbot-close:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .chatbot-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background: #f8f9fa;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .message {
            max-width: 80%;
            padding: 12px 16px;
            border-radius: 18px;
            word-wrap: break-word;
            animation: messageSlide 0.3s ease;
        }

        .message.user {
            background: #667eea;
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 5px;
        }

        .message.bot {
            background: white;
            color: #333;
            align-self: flex-start;
            border-bottom-left-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .typing-indicator {
            display: none;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            background: white;
            border-radius: 18px;
            align-self: flex-start;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .typing-dots {
            display: flex;
            gap: 4px;
        }

        .typing-dots span {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #667eea;
            animation: typing 1.4s infinite ease-in-out;
        }

        .typing-dots span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dots span:nth-child(3) {
            animation-delay: 0.4s;
        }

        .chatbot-input {
            padding: 20px;
            background: white;
            border-top: 1px solid #e0e0e0;
            display: flex;
            gap: 10px;
        }

        .chatbot-input input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 25px;
            outline: none;
            font-size: 14px;
        }

        .chatbot-input input:focus {
            border-color: #667eea;
        }

        .chatbot-send {
            width: 45px;
            height: 45px;
            background: #667eea;
            border: none;
            border-radius: 50%;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s;
        }

        .chatbot-send:hover:not(:disabled) {
            background: #5a6fd8;
        }

        .chatbot-send:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        @keyframes messageSlide {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes typing {
            0%, 60%, 100% {
                transform: translateY(0);
            }
            30% {
                transform: translateY(-10px);
            }
        }

        /* Responsive */
        @media (max-width: 640px) {
            .chatbot-container {
                left: 10px;
                right: 10px;
                width: auto;
                bottom: 80px;
            }

            .chatbot-icon {
                left: 15px;
                bottom: 15px;
            }
        }
    </style>
</head>
<body>
<!-- Chatbot -->
<div class="chatbot-icon" id="chatbotIcon" onclick="toggleChatbot()">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
    </svg>
</div>

<div class="chatbot-container" id="chatbotContainer">
    <div class="chatbot-header">
        <button class="chatbot-close" onclick="toggleChatbot()">×</button>
        <h3 class="font-bold text-lg">🧁 Sweet Delights Assistant</h3>
        <p class="text-sm opacity-90">Hỗ trợ tư vấn bánh ngọt 24/7</p>
    </div>

    <div class="chatbot-messages" id="chatbotMessages">
        <div class="message bot">
            Xin chào! 👋 Tôi là trợ lý ảo của Sweet Delights. Tôi có thể giúp bạn tìm hiểu về các loại bánh ngọt, giá cả, và cách đặt hàng. Bạn cần hỗ trợ gì nhé? 🧁✨
        </div>
    </div>

    <div class="typing-indicator" id="typingIndicator">
        <span>Đang trả lời</span>
        <div class="typing-dots">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <div class="chatbot-input">
        <input type="text" id="chatbotInput" placeholder="Nhập câu hỏi của bạn..." maxlength="500">
        <button class="chatbot-send" id="chatbotSend" onclick="sendMessage()">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
            </svg>
        </button>
    </div>
</div>
<!-- Navigation -->
<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-30">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <img src="{{ asset('images/sweet-delights-logo.svg') }}" alt="Sweet Delights Logo" class="h-25 w-auto">
                </div>
                <div class="hidden md:ml-6 md:flex md:space-x-8">
                    <a href="#home"
                       class="nav-link active text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium transition duration-200">
                        Trang Chủ
                    </a>
                    <a href="#products"
                       class="nav-link text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium transition duration-200">
                        Sản Phẩm
                    </a>
                    <a href="#categories"
                       class="nav-link text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium transition duration-200">
                        Danh Mục
                    </a>
                    <a href="#about"
                       class="nav-link text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium transition duration-200">
                        Về Chúng Tôi
                    </a>
                    <a href="#contact"
                       class="nav-link text-gray-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 text-sm font-medium transition duration-200">
                        Liên Hệ
                    </a>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <!-- Cart Icon -->
                <button @if(auth()->check()) onclick="openCartOverlay()" @else onclick="showLoginPrompt()" @endif class="relative p-2 text-gray-600 hover:text-gray-900 transition duration-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01" />
                    </svg>
                    <span
                        class="absolute -top-1 -right-1 bg-pink-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        {{ auth()->check() ? \App\Models\CartItem::where('user_id', auth()->id())->count() : 0 }}
                    </span>
                </button>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="text-sm text-gray-700 hover:text-gray-900 transition duration-200">Dashboard</a>
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                               class="text-sm text-pink-600 hover:text-pink-800 font-medium transition duration-200">Admin</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="text-sm text-gray-700 hover:text-gray-900 transition duration-200">Đăng Nhập</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="ml-4 text-sm text-gray-700 hover:text-gray-900 transition duration-200">Đăng Ký</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>
</nav>

<!-- Cart Pop-up Overlay -->
<div id="cartOverlay">
    <div id="cartContent">
        @php
            $cartItems = auth()->check() ? \App\Models\CartItem::where('user_id', auth()->id())->get() : collect([]);
            $totalPrice = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
            });
        @endphp
        @include('pages.cart.overlay', compact('cartItems', 'totalPrice'))
    </div>
</div>

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
                    <a href="{{ route('products.index') }}"
                       class="bg-pink-600 text-white px-8 py-4 rounded-lg text-lg font-medium hover:bg-pink-700 transition duration-300 transform hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Mua Ngay
                    </a>
                    <a href="#products"
                       class="bg-white text-pink-600 px-8 py-4 rounded-lg text-lg font-medium border-2 border-pink-600 hover:bg-pink-50 transition duration-300 shadow-lg">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                        Xem Sản Phẩm
                    </a>
                </div>
            </div>
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&h=600&fit=crop"
                     alt="Bánh ngọt tươi ngon"
                     class="rounded-3xl shadow-2xl transform rotate-3 hover:rotate-0 transition duration-500">
                <div
                    class="absolute -top-4 -left-4 bg-yellow-400 text-yellow-900 px-4 py-2 rounded-full font-bold transform -rotate-12 shadow-lg">
                    🔥 Hot Sale!
                </div>
                <div
                    class="absolute -bottom-4 -right-4 bg-pink-500 text-white px-4 py-2 rounded-full font-bold transform rotate-12 shadow-lg">
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
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Khám phá các loại bánh ngọt đa dạng được yêu thích
                nhất</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @php
                $categories = \App\Models\Category::active()->withCount('products')->get();
            @endphp

            @forelse($categories as $category)
                <div class="text-center group cursor-pointer card-hover">
                    <div class="bg-white rounded-2xl p-6 shadow-lg group-hover:shadow-xl transition duration-300">
                        <div
                            class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-pink-100 to-purple-100 rounded-full flex items-center justify-center group-hover:from-pink-200 group-hover:to-purple-200 transition duration-300">
                            @if($category->image_url)
                                <img src="{{ $category->image_url }}" alt="{{ $category->name }}"
                                     class="w-12 h-12 rounded-full object-cover">
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
            <a href="{{ route('products.index') }}"
               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-pink-600 bg-pink-100 hover:bg-pink-200 transition duration-300">
                Xem Tất Cả Danh Mục
                <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
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

        <div class="relative">
            <div class="flex items-start gap-8 overflow-x-auto scroll-smooth snap-x hide-scrollbar" id="productScrollContainer">
                @php
                    $featuredProducts = \App\Models\Product::with('category')->active()->featured()->take(8)->get();
                @endphp

                @forelse($featuredProducts as $product)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 card-hover group min-w-[280px] snap-center draggable">
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
                                    <span class="bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-full shadow-sm">⭐ Nổi bật</span>
                                </div>
                                @if(($product->stock_quantity ?? 0) <= 0)
                                    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                        <span class="text-white font-bold text-lg">Hết hàng</span>
                                    </div>
                                @endif
                            </div>
                        </a>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-medium text-pink-600 bg-pink-50 px-3 py-1 rounded-full">{{ $product->category->name ?? 'Khác' }}</span>
                                @if(($product->stock_quantity ?? 0) > 0)
                                    <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full">Còn hàng</span>
                                @else
                                    <span class="text-xs text-red-600 bg-red-50 px-2 py-1 rounded-full">Hết hàng</span>
                                @endif
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-1">
                                <a href="{{ route('products.show', $product) }}" class="hover:text-pink-600 transition duration-300">{{ $product->name }}</a>
                            </h3>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ Str::limit($product->description, 60) }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xl font-bold text-pink-600">{{ $product->formatted_price }}</span>
                                @if(($product->stock_quantity ?? 0) > 0)
                                    @if(auth()->check())
                                        <form action="{{ route('cart.add') }}" method="POST" style="display:inline" class="cart-form">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1"> <!-- Đảm bảo quantity là 1 -->
                                            <button type="submit" class="add-to-cart bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 transition duration-300 text-sm font-medium transform hover:scale-105" data-product-id="{{ $product->id }}" data-loading-text="...">
                                                Thêm vào giỏ
                                            </button>
                                        </form>
                                    @else
                                        <button onclick="showLoginPrompt()" class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 transition duration-300 text-sm font-medium transform hover:scale-105">
                                            Thêm vào giỏ
                                        </button>
                                    @endif
                                @else
                                    <button class="bg-gray-300 text-gray-500 px-4 py-2 rounded-lg cursor-not-allowed text-sm" disabled>Hết hàng</button>
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
            <!-- Nút điều hướng -->
            <button id="scrollLeft" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-pink-600 text-white p-3 rounded-full hover:bg-pink-700 transition duration-300 shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button id="scrollRight" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-pink-600 text-white p-3 rounded-full hover:bg-pink-700 transition duration-300 shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
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
                    Sweet Delights là tiệm bánh ngọt gia đình với hơn 10 năm kinh nghiệm trong việc tạo ra những
                    chiếc bánh ngọt tươi ngon và đầy hương vị.
                </p>
                <p class="text-gray-600 mb-8 text-lg leading-relaxed">
                    Chúng tôi cam kết sử dụng những nguyên liệu tươi ngon nhất và công thức truyền thống được truyền
                    qua nhiều thế hệ để mang đến cho bạn những trải nghiệm ẩm thực tuyệt vời nhất.
                </p>

                <div class="space-y-4">
                    <div class="flex items-center">
                        <div class="bg-green-100 rounded-full p-2 mr-4">
                            <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                      clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="text-gray-700 font-medium">Nguyên liệu tươi ngon 100%</span>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-green-100 rounded-full p-2 mr-4">
                            <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                      clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="text-gray-700 font-medium">Làm thủ công với tình yêu</span>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-green-100 rounded-full p-2 mr-4">
                            <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                      clip-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="text-gray-700 font-medium">Giao hàng nhanh chóng</span>
                    </div>
                    <div class="flex items-center">
                        <div class="bg-green-100 rounded-full p-2 mr-4">
                            <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                      clip-rule="evenodd" />
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
                    <img src="{{ asset('images/278525968_698464674814175_5271679419822982177_n.jpg') }}"
                         alt="Trần Duy Khánh"
                         class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <h4 class="font-semibold text-gray-900">Trần Duy Khánh</h4>
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
                    <img src="{{ asset('images/IMG_2814.JPG') }}"
                         alt="Mai Thái Huy"
                         class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <h4 class="font-semibold text-gray-900">Mai Thái Huy</h4>
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
                    <img src="{{ asset('images/hihi.JPG') }}"
                         alt="Lưu Đức Quang"
                         class="w-12 h-12 rounded-full mr-4">
                    <div>
                        <h4 class="font-semibold text-gray-900">Lưu Đức Quang</h4>
                        <p class="text-gray-500 text-sm">Khách hàng VIP</p>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-4 text-xl">Địa Chỉ</h3>
                <p class="text-gray-600 leading-relaxed">
                    Học viện Ngân Hàng<br>
                    TP. Hà Nội<br>
                    Việt Nam
                </p>
            </div>

            <div class="bg-white rounded-2xl p-8 text-center shadow-lg card-hover">
                <div class="bg-pink-100 rounded-full w-16 h-16 mx-auto mb-6 flex items-center justify-center">
                    <svg class="h-8 w-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-4 text-xl">Giờ Mở Cửa</h3>
                <p class="text-gray-600 leading-relaxed">
                    <strong>Thứ 2 - Thứ 7:</strong> 8:00 - 22:00<br>
                    <strong>Chủ Nhật:</strong> 9:00 - 21:00<br>
                    <span class="text-pink-600 font-medium">Giao hàng miễn phí từ 500k</span>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
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
        <p class="text-gray-300 mb-8 max-w-2xl mx-auto">Đăng ký để nhận thông tin về sản phẩm mới, khuyến mãi đặc
            biệt và những mẹo làm bánh thú vị</p>

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
<!-- Floating Contact Icons -->
<div class="floating-contact">
    <!-- Phone -->
    <a href="tel:0123456789" class="contact-icon phone-icon" data-tooltip="Gọi điện: 0123 456 789">
        <svg fill="currentColor" viewBox="0 0 24 24">
            <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" />
        </svg>
    </a>

    <!-- Zalo -->
    <a href="https://zalo.me/0987654321" target="_blank" class="contact-icon zalo-icon" data-tooltip="Chat Zalo: 0987 654 321">
        <svg fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
            <text x="12" y="16" text-anchor="middle" fill="white" font-size="8" font-weight="bold">Z</text>
        </svg>
    </a>

    <!-- Email -->
    <a href="mailto:hkkhanhpro@gmail.com" class="contact-icon email-icon" data-tooltip="Email: hkkhanhpro@gmail.com">
        <svg fill="currentColor" viewBox="0 0 24 24">
            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
        </svg>
    </a>

    <!-- Facebook -->
    <a href="https://www.facebook.com/khanhhkly23/" target="_blank" class="contact-icon facebook-icon" data-tooltip="Facebook Sweet Delights">
        <svg fill="currentColor" viewBox="0 0 24 24">
            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
        </svg>
    </a>

    <!-- Scroll to top -->
    <div class="contact-icon scroll-top-icon" id="scrollToTop" data-tooltip="Lên đầu trang">
        <svg fill="currentColor" viewBox="0 0 24 24">
            <path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z" />
        </svg>
    </div>
</div>
</body>
<!-- Footer -->
<footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <img src="{{ asset('images/sweet-delights-logo.svg') }}" alt="Sweet Delights Logo" class="h-20 w-auto mb-4">
                <p class="text-gray-400 mb-4">Bánh ngọt tươi ngon được làm với tình yêu và sự tận tâm từ năm 2014.</p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition duration-200">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-200">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-200">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.097.118.112.165.085.289-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z" />
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
                        Học viện Ngân Hàng<br>TP. Hà Nội
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
                        hkkhanhpro@gmail.com
                    </p>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
            <p>© 2024 Sweet Delights. Tất cả quyền được bảo lưu. Thiết kế bởi Family Guys Team.</p>
        </div>
    </div>
</footer>

<script>
    let isCartActionRunning = false;

    // Hiển thị thông báo
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg text-white z-50 transition-all duration-300 transform translate-x-full ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        }`;
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Xử lý hành động giỏ hàng
    async function handleCartAction(element, event) {
        event.preventDefault();
        if (isCartActionRunning) return;
        isCartActionRunning = true;

        let url, method = 'POST',
            formData;

        try {
            if (element.classList.contains('add-to-cart')) {
                const form = element.closest('form');
                url = form.action;
                formData = new FormData(form);
                if (!formData.get('quantity')) {
                    formData.set('quantity', 1);
                }
            } else if (element.closest('form')?.classList.contains('cart-form')) {
                url = '{{ route("cart.update") }}';
                formData = new FormData(element.closest('form'));
            } else if (element.classList.contains('delete-btn')) {
                url = element.href;
                formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            } else {
                isCartActionRunning = false;
                return;
            }

            // Hiệu ứng loading
            element.disabled = true;
            const originalText = element.textContent;
            if (element.classList.contains('add-to-cart')) {
                element.textContent = element.dataset.loadingText || 'Đang thêm...';
            }

            const response = await fetch(url, {
                method,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData,
            });

            const data = await response.json();

            element.disabled = false;
            if (element.classList.contains('add-to-cart')) {
                element.textContent = originalText;
            }

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Có lỗi xảy ra. Vui lòng thử lại!');
            }

            document.getElementById('cartContent').innerHTML = data.cartHtml;
            document.querySelector('.relative span').textContent = data.cartCount;
            showNotification(data.message || 'Cập nhật giỏ hàng thành công!');
            if (element.classList.contains('add-to-cart')) {
                openCartOverlay();
            }

        } catch (error) {
            console.error('Cart error:', error);
            showNotification(error.message || 'Có lỗi xảy ra khi xử lý giỏ hàng.', 'error');
            element.disabled = false;
            if (element.classList.contains('add-to-cart')) {
                element.textContent = originalText;
            }
        } finally {
            isCartActionRunning = false;
        }
    }

    // Gắn sự kiện bằng delegation
    function delegateCartEvents() {
        document.addEventListener('click', function(e) {
            const addBtn = e.target.closest('.add-to-cart');
            const updateBtn = e.target.closest('.cart-form button');
            const deleteBtn = e.target.closest('.delete-btn');

            if (addBtn) return handleCartAction(addBtn, e);
            if (updateBtn) return handleCartAction(updateBtn, e);
            if (deleteBtn) return handleCartAction(deleteBtn, e);
        });
    }

    // Hiển thị overlay giỏ hàng
    function openCartOverlay() {
        const overlay = document.getElementById('cartOverlay');
        overlay.style.display = 'flex';
        setTimeout(() => overlay.classList.add('opacity-100'), 10);
        document.body.style.overflow = 'hidden';
    }

    // Đóng overlay giỏ hàng
    function closeCartOverlay() {
        const overlay = document.getElementById('cartOverlay');
        overlay.classList.remove('opacity-100');
        setTimeout(() => {
            overlay.style.display = 'none';
            document.body.style.overflow = '';
        }, 300);
    }

    // Nhắc người dùng đăng nhập
    function showLoginPrompt() {
        showNotification('Vui lòng đăng nhập để thực hiện hành động này!', 'error');
        setTimeout(() => {
            window.location.href = '{{ route("login") }}';
        }, 2000);
    }

    // Scroll sản phẩm
    function initProductScrolling() {
        const container = document.getElementById('productScrollContainer');
        const scrollLeftBtn = document.getElementById('scrollLeft');
        const scrollRightBtn = document.getElementById('scrollRight');

        if (!container || !scrollLeftBtn || !scrollRightBtn) return;

        const scrollAmount = 300;

        scrollLeftBtn.addEventListener('click', () => {
            container.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        });

        scrollRightBtn.addEventListener('click', () => {
            container.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        });

        let isDragging = false;
        let startX, scrollLeft;

        container.addEventListener('mousedown', (e) => {
            isDragging = true;
            container.classList.add('cursor-grabbing');
            startX = e.pageX - container.offsetLeft;
            scrollLeft = container.scrollLeft;
        });

        container.addEventListener('mouseleave', () => {
            isDragging = false;
            container.classList.remove('cursor-grabbing');
        });

        container.addEventListener('mouseup', () => {
            isDragging = false;
            container.classList.remove('cursor-grabbing');
        });

        container.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            const x = e.pageX - container.offsetLeft;
            const walk = (x - startX) * 2;
            container.scrollLeft = scrollLeft - walk;
        });

        container.addEventListener('touchstart', (e) => {
            isDragging = true;
            startX = e.touches[0].pageX - container.offsetLeft;
            scrollLeft = container.scrollLeft;
        });

        container.addEventListener('touchend', () => {
            isDragging = false;
        });

        container.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            const x = e.touches[0].pageX - container.offsetLeft;
            const walk = (x - startX) * 2;
            container.scrollLeft = scrollLeft - walk;
        });
    }

    // Nút scroll to top
    function initScrollToTop() {
        const scrollToTopBtn = document.getElementById('scrollToTop');
        if (!scrollToTopBtn) return;

        window.addEventListener('scroll', () => {
            scrollToTopBtn.classList.toggle('show', window.scrollY > 300);
        });

        scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // Khởi tạo sau khi DOM sẵn sàng
    document.addEventListener('DOMContentLoaded', () => {
        delegateCartEvents();
        initProductScrolling();
        initScrollToTop();

        const closeCartBtn = document.getElementById('closeCart');
        if (closeCartBtn) {
            closeCartBtn.addEventListener('click', closeCartOverlay);
        }

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
    // Chatbot Functions
    let chatbotOpen = false;

    function toggleChatbot() {
        const container = document.getElementById('chatbotContainer');
        const icon = document.getElementById('chatbotIcon');

        chatbotOpen = !chatbotOpen;

        if (chatbotOpen) {
            container.style.display = 'flex';
            setTimeout(() => {
                container.style.animation = 'messageSlide 0.3s ease';
            }, 10);
            // Focus vào input
            setTimeout(() => {
                document.getElementById('chatbotInput').focus();
            }, 100);
        } else {
            container.style.display = 'none';
        }
    }

    function sendMessage() {
        const input = document.getElementById('chatbotInput');
        const message = input.value.trim();

        if (!message) return;

        // Thêm tin nhắn của user
        addMessage(message, 'user');
        input.value = '';

        // Hiển thị typing indicator
        showTyping(true);

        // Gửi đến server
        fetch('/chatbot/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ message: message })
        })
            .then(response => response.json())
            .then(data => {
                showTyping(false);
                if (data.success) {
                    addMessage(data.message, 'bot');
                } else {
                    addMessage('Xin lỗi, tôi đang gặp sự cố. Vui lòng liên hệ hotline 0123 456 789! 😊', 'bot');
                }
            })
            .catch(error => {
                console.error('Chatbot error:', error);
                showTyping(false);
                addMessage('Không thể kết nối. Vui lòng thử lại sau! 🔄', 'bot');
            });
    }

    function addMessage(text, sender) {
        const messagesContainer = document.getElementById('chatbotMessages');
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message', sender);
        messageDiv.textContent = text;

        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function showTyping(show) {
        const typingIndicator = document.getElementById('typingIndicator');
        const sendButton = document.getElementById('chatbotSend');

        typingIndicator.style.display = show ? 'flex' : 'none';
        sendButton.disabled = show;

        if (show) {
            const messagesContainer = document.getElementById('chatbotMessages');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    }

    // Enter để gửi tin nhắn
    document.addEventListener('DOMContentLoaded', function() {
        const chatInput = document.getElementById('chatbotInput');
        if (chatInput) {
            chatInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });
        }
    });
</script>
</html>
