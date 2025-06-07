<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
<div class="min-h-screen flex">
    <!-- Sidebar -->
    <div class="w-64 bg-gray-800 shadow-lg">
        <div class="p-6">
            <h2 class="text-white text-2xl font-bold">🧁 Admin Panel</h2>
        </div>

        <nav class="mt-6">
            <div class="px-6 py-3">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Dashboard
                </a>
            </div>

            <div class="px-6 py-3">
                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    Danh Mục
                </a>
            </div>

            <div class="px-6 py-3">
                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition duration-200 {{ request()->routeIs('admin.products.*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Sản Phẩm
                </a>
            </div>

            <div class="px-6 py-3">
                <a href="{{ route('admin.orders.index') }}"
                   class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition duration-200 {{ request()->routeIs('admin.orders.*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Đơn Hàng
                </a>
            </div>

            <div class="px-6 py-3">
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-gray-700 text-white' : '' }}">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                    Người Dùng
                </a>
            </div>

            <div class="border-t border-gray-700 mt-6 pt-6">
                <div class="px-6 py-3">
                    <a href="{{ route('products.index') }}"
                       class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white rounded-md transition duration-200"
                       target="_blank">
                        <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Xem Website
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="flex justify-between items-center px-6 py-4">
                @isset($header)
                    <div class="text-xl font-semibold text-gray-800">
                        {{ $header }}
                    </div>
                @else
                    <div class="text-xl font-semibold text-gray-800">
                        Admin Dashboard
                    </div>
                @endisset

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Xin chào, {{ Auth::user()->name }}</span>

                    <!-- Dropdown với Alpine.js -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <img class="h-8 w-8 rounded-full bg-gray-300"
                                 src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF"
                                 alt="{{ Auth::user()->name }}">
                        </button>

                        <div x-show="open"
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                            <div class="py-1">
                                <a href="{{ route('dashboard') }}"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    User Dashboard
                                </a>
                                <a href="{{ route('profile.edit') }}"
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Cài Đặt Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Đăng Xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-6 py-8">
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>
</div>

<!-- Debug script để tìm auto reload -->
<script>
    console.log('Admin layout loaded');

    // Chặn auto reload và log nguồn gốc
    let originalReload = location.reload;
    location.reload = function() {
        console.error('🚫 BLOCKED: Page reload attempt detected!');
        console.trace('Reload source:');
        // return originalReload.apply(this, arguments); // Uncomment để cho phép reload
    };

    // Log tất cả setInterval/setTimeout
    let intervals = [];
    let timeouts = [];

    let originalSetInterval = setInterval;
    window.setInterval = function(callback, delay) {
        console.log('⏰ setInterval created:', delay + 'ms', callback.toString().substring(0, 100));
        let id = originalSetInterval.apply(this, arguments);
        intervals.push({id, delay, callback: callback.toString()});
        return id;
    };

    let originalSetTimeout = setTimeout;
    window.setTimeout = function(callback, delay) {
        console.log('⏱️ setTimeout created:', delay + 'ms', callback.toString().substring(0, 100));
        let id = originalSetTimeout.apply(this, arguments);
        timeouts.push({id, delay, callback: callback.toString()});
        return id;
    };

    // Command để xem tất cả interval/timeout đang chạy
    window.showTimers = function() {
        console.log('🔍 Active intervals:', intervals);
        console.log('🔍 Active timeouts:', timeouts);
    };
</script>
</body>
</html>
