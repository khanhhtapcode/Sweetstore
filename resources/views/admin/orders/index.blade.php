<x-admin-layout>
    <x-slot name="header">
        Quản Lý Đơn Hàng 📋
    </x-slot>

    <!-- Debug script -->
    <script>
        console.log('Orders page loaded at:', new Date());
    </script>

    <!-- Thống kê nhanh -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-yellow-50 rounded-lg p-6 border border-yellow-200">
            <div class="flex items-center">
                <div class="text-yellow-600 text-3xl mr-4">⏳</div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Chờ xác nhận</h3>
                    <p class="text-2xl font-bold text-yellow-600">{{ \App\Models\Order::where('status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
            <div class="flex items-center">
                <div class="text-blue-600 text-3xl mr-4">👨‍🍳</div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Đang chuẩn bị</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ \App\Models\Order::where('status', 'preparing')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-green-50 rounded-lg p-6 border border-green-200">
            <div class="flex items-center">
                <div class="text-green-600 text-3xl mr-4">✅</div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Đã giao</h3>
                    <p class="text-2xl font-bold text-green-600">{{ \App\Models\Order::where('status', 'delivered')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-red-50 rounded-lg p-6 border border-red-200">
            <div class="flex items-center">
                <div class="text-red-600 text-3xl mr-4">❌</div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Đã hủy</h3>
                    <p class="text-2xl font-bold text-red-600">{{ \App\Models\Order::where('status', 'cancelled')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Thao tác nhanh -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Thao Tác Nhanh</h3>
        <div class="flex space-x-4">
            <a href="{{ route('admin.orders.create') }}"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Tạo Đơn Hàng
            </a>
            <!-- Tạm thời disable nút refresh -->
            <button onclick="console.log('Refresh disabled for debugging')"
                    class="bg-gray-300 text-gray-500 font-bold py-2 px-4 rounded cursor-not-allowed"
                    disabled>
                🔄 Làm Mới (Disabled)
            </button>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Bộ Lọc</h3>
        <form method="GET" action="{{ route('admin.orders.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                    <input type="text" name="search" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                           placeholder="Mã đơn hàng, tên khách..." value="{{ request('search') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                    <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Đang chuẩn bị</option>
                        <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Sẵn sàng giao</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Thanh toán</label>
                    <select name="payment_method" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                        <option value="">Tất cả phương thức</option>
                        <option value="cod" {{ request('payment_method') == 'cod' ? 'selected' : '' }}>💵 COD</option>
                        <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>🏦 Chuyển khoản</option>
                        <option value="credit_card" {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>💳 Thẻ tín dụng</option>
                        <option value="momo" {{ request('payment_method') == 'momo' ? 'selected' : '' }}>MoMo</option>
                        <option value="zalopay" {{ request('payment_method') == 'zalopay' ? 'selected' : '' }}>💜 ZaloPay</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Từ ngày</label>
                    <input type="date" name="date_from" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                           value="{{ request('date_from') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Đến ngày</label>
                    <input type="date" name="date_to" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                           value="{{ request('date_to') }}">
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                        🔍 Tìm
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm">
                        🔄 Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Thông báo -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Bảng đơn hàng -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Danh Sách Đơn Hàng</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã ĐH</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đặt</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thanh toán</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @php
                    $orders = \App\Models\Order::with(['orderItems.product'])->latest()->paginate(15);
                @endphp
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->customer_email }}</div>
                            <div class="text-sm text-gray-500">{{ $order->customer_phone }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $order->created_at->format('d/m/Y') }}</div>
                            <div class="text-sm text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-blue-600">{{ number_format($order->total_amount) }}₫</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusConfig = [
                                    'pending' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Chờ xác nhận'],
                                    'confirmed' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Đã xác nhận'],
                                    'preparing' => ['class' => 'bg-purple-100 text-purple-800', 'text' => 'Đang chuẩn bị'],
                                    'ready' => ['class' => 'bg-indigo-100 text-indigo-800', 'text' => 'Sẵn sàng giao'],
                                    'delivered' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Đã giao'],
                                    'cancelled' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Đã hủy']
                                ];
                                $config = $statusConfig[$order->status] ?? $statusConfig['pending'];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['class'] }}">
                                    {{ $config['text'] }}
                                </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $paymentConfig = [
                                    'cod' => [
                                        'class' => 'bg-orange-100 text-orange-800',
                                        'text' => 'COD',
                                        'icon' => '💵'
                                    ],
                                    'bank_transfer' => [
                                        'class' => 'bg-blue-100 text-blue-800',
                                        'text' => 'Chuyển khoản',
                                        'icon' => '🏦'
                                    ],
                                    'credit_card' => [
                                        'class' => 'bg-green-100 text-green-800',
                                        'text' => 'Thẻ tín dụng',
                                        'icon' => '💳'
                                    ],
                                    'momo' => [
                                        'class' => 'bg-pink-100 text-pink-800',
                                        'text' => 'MoMo',
                                        'logo' => 'https://homepage.momocdn.net/fileuploads/svg/momo-file-240411162904.svg'
                                    ],
                                    'zalopay' => [
                                        'class' => 'bg-purple-100 text-purple-800',
                                        'text' => 'ZaloPay',
                                        'icon' => '💜'
                                    ]
                                ];

                                // An toàn hơn - kiểm tra null và isset
                                $currentPaymentMethod = $order->payment_method ?? 'cod';
                                $payment = $paymentConfig[$currentPaymentMethod] ?? [
                                    'class' => 'bg-gray-100 text-gray-800',
                                    'text' => 'Không xác định',
                                    'icon' => '❓'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payment['class'] }}">
                                 @if(isset($payment['logo']))
                                    <img src="{{ $payment['logo'] }}" alt="{{ $payment['text'] }}" class="w-4 h-4 mr-1">
                                @else
                                    <span class="mr-1">{{ $payment['icon'] ?? '' }}</span>
                                @endif
                                {{ $payment['text'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                               class="text-blue-600 hover:text-blue-900 inline-block">👁️ Xem</a>

                            @if($order->status !== 'delivered' && $order->status !== 'cancelled')
                                <div class="relative inline-block text-left">
                                    <button type="button" onclick="toggleDropdown({{ $order->id }})"
                                            class="text-green-600 hover:text-green-900">✏️ Cập nhật</button>
                                    <div id="dropdown-{{ $order->id }}" class="hidden absolute right-0 z-10 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg">
                                        <div class="py-1">
                                            @if($order->status === 'pending')
                                                <a href="#" onclick="updateStatus({{ $order->id }}, 'confirmed')"
                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">✅ Xác nhận</a>
                                            @endif
                                            @if(in_array($order->status, ['confirmed', 'preparing']))
                                                <a href="#" onclick="updateStatus({{ $order->id }}, 'preparing')"
                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">👨‍🍳 Đang chuẩn bị</a>
                                            @endif
                                            @if(in_array($order->status, ['confirmed', 'preparing', 'ready']))
                                                <a href="#" onclick="updateStatus({{ $order->id }}, 'ready')"
                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">🚚 Sẵn sàng giao</a>
                                            @endif
                                            @if($order->status !== 'delivered')
                                                <a href="#" onclick="updateStatus({{ $order->id }}, 'delivered')"
                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">✅ Đã giao</a>
                                            @endif
                                            <hr class="my-1">
                                            <a href="#" onclick="updateStatus({{ $order->id }}, 'cancelled')"
                                               class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">❌ Hủy đơn</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="text-4xl mb-4">📦</div>
                            <div class="text-lg font-medium">Chưa có đơn hàng nào</div>
                            <div class="text-sm mt-2">
                                <a href="{{ route('admin.orders.create') }}" class="text-blue-500 hover:underline">
                                    Tạo đơn hàng đầu tiên →
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <script>
        function toggleDropdown(orderId) {
            console.log('Toggling dropdown for order:', orderId);
            const dropdown = document.getElementById(`dropdown-${orderId}`);
            // Hide all other dropdowns
            document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
                if (el.id !== `dropdown-${orderId}`) {
                    el.classList.add('hidden');
                }
            });
            // Toggle current dropdown
            dropdown.classList.toggle('hidden');
        }

        function updateStatus(orderId, status) {
            console.log('Updating status for order:', orderId, 'to:', status);
            const statusTexts = {
                'confirmed': 'xác nhận',
                'preparing': 'chuyển sang đang chuẩn bị',
                'ready': 'chuyển sang sẵn sàng giao',
                'delivered': 'đánh dấu đã giao',
                'cancelled': 'hủy'
            };

            if (confirm(`Bạn có chắc chắn muốn ${statusTexts[status]} đơn hàng này?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/orders/${orderId}/status`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PATCH';
                form.appendChild(methodField);

                const statusField = document.createElement('input');
                statusField.type = 'hidden';
                statusField.name = 'status';
                statusField.value = status;
                form.appendChild(statusField);

                document.body.appendChild(form);
                form.submit();
            }
        }

        if (typeof window.originalSetIntervalBackup === 'undefined') {
            console.log('🔧 Setting up interval controls...');

            // Backup original functions
            window.originalSetIntervalBackup = window.setInterval;
            window.originalSetTimeoutBackup = window.setTimeout;
            window.originalLocationReload = window.location.reload;

            // Override location.reload
            window.location.reload = function() {
                console.log('🚫 BLOCKED: Page reload attempt!');
                console.trace('Reload source:');
                // Uncomment next line to allow reload: return window.originalLocationReload.apply(this, arguments);
            };

            // Override setInterval
            window.setInterval = function(callback, delay) {
                console.log('⏰ setInterval attempt:', delay + 'ms', callback.toString().substring(0, 50) + '...');

                // Chỉ cho phép interval 5000ms cho alert dismissal
                if (delay === 5000 && callback.toString().includes('alert')) {
                    console.log('✅ ALLOWED: Alert dismissal interval');
                    return window.originalSetIntervalBackup.apply(this, arguments);
                }

                // Chặn tất cả intervals khác
                console.log('❌ BLOCKED: Interval blocked for debugging');
                return 999999; // Return fake ID
            };

            // Override setTimeout
            window.setTimeout = function(callback, delay) {
                console.log('⏱️ setTimeout attempt:', delay + 'ms', callback.toString().substring(0, 50) + '...');

                // Cho phép timeout cho alert dismissal và UI updates
                if (delay <= 5000) {
                    console.log('✅ ALLOWED: Short timeout');
                    return window.originalSetTimeoutBackup.apply(this, arguments);
                }

                console.log('❌ BLOCKED: Long timeout blocked');
                return 999999;
            };

            // Clear existing intervals
            console.log('🧹 Clearing existing intervals...');
            for(let i = 1; i < 99999; i++) {
                try {
                    clearInterval(i);
                } catch(e) {}
            }

            console.log('🛡️ JavaScript protection active');
        } else {
            console.log('⚡ Protection already active');
        }

        // Monitor payment method visibility
        function checkPaymentMethods() {
            const creditCardElements = Array.from(document.querySelectorAll('span')).filter(el =>
                el.textContent.trim() === 'Thẻ tín dụng'
            );

            console.log('💳 Credit card payment methods found:', creditCardElements.length);

            creditCardElements.forEach((el, index) => {
                const isVisible = el.offsetParent !== null;
                const computedStyle = window.getComputedStyle(el);

                console.log(`💳 Element ${index}:`, {
                    text: el.textContent,
                    visible: isVisible,
                    display: computedStyle.display,
                    visibility: computedStyle.visibility,
                    opacity: computedStyle.opacity
                });
            });
        }

        // Check immediately and every 3 seconds
        checkPaymentMethods();
        window.originalSetTimeoutBackup(function() {
            console.log('🔍 Checking payment methods after 3 seconds...');
            checkPaymentMethods();
        }, 3000);

        window.originalSetTimeoutBackup(function() {
            console.log('🔍 Checking payment methods after 10 seconds...');
            checkPaymentMethods();
        }, 10000);
    </script>
</x-admin-layout>
