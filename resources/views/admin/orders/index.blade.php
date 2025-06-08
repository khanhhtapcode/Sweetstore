<x-admin-layout>
    <x-slot name="header">
        Quản Lý Đơn Hàng 📋
    </x-slot>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Thống kê nhanh -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
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
                <div class="text-blue-600 text-3xl mr-4">📦</div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Sẵn sàng giao</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ \App\Models\Order::where('status', 'ready')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-purple-50 rounded-lg p-6 border border-purple-200">
            <div class="flex items-center">
                <div class="text-purple-600 text-3xl mr-4">🚚</div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Đang giao</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ \App\Models\Order::whereIn('status', ['assigned', 'picked_up', 'delivering'])->count() }}</p>
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
                <div class="text-red-600 text-3xl mr-4">🚨</div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Cần tài xế</h3>
                    <p class="text-2xl font-bold text-red-600">{{ \App\Models\Order::where('status', 'ready')->whereNull('driver_id')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Thao tác nhanh -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Thao Tác Nhanh</h3>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.orders.create') }}"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                ➕ Tạo Đơn Hàng
            </a>
            <button onclick="autoAssignDrivers()"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                🤖 Tự Động Gán Tài Xế
            </button>
            <a href="{{ route('admin.orders.export', request()->query()) }}"
               class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                📊 Xuất Excel
            </a>
            <button onclick="location.reload()"
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                🔄 Làm Mới
            </button>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Bộ Lọc</h3>
        <form method="GET" action="{{ route('admin.orders.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-7 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                    <input type="text" name="search" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                           placeholder="Mã đơn hàng, tên khách..." value="{{ request('search') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                    <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Chờ xác nhận</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>✅ Đã xác nhận</option>
                        <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>👨‍🍳 Đang chuẩn bị</option>
                        <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>📦 Sẵn sàng giao</option>
                        <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>🚚 Đã gán tài xế</option>
                        <option value="picked_up" {{ request('status') == 'picked_up' ? 'selected' : '' }}>📋 Đã lấy hàng</option>
                        <option value="delivering" {{ request('status') == 'delivering' ? 'selected' : '' }}>🚛 Đang giao</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>✅ Đã giao</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>❌ Đã hủy</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tài xế</label>
                    <select name="driver_id" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                        <option value="">Tất cả tài xế</option>
                        <option value="0" {{ request('driver_id') === '0' ? 'selected' : '' }}>🚨 Chưa gán tài xế</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ request('driver_id') == $driver->id ? 'selected' : '' }}>
                                {{ $driver->driver_code }} - {{ $driver->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Thanh toán</label>
                    <select name="payment_method" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
                        <option value="">Tất cả phương thức</option>
                        <option value="cod" {{ request('payment_method') == 'cod' ? 'selected' : '' }}>💵 COD</option>
                        <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>🏦 Chuyển khoản</option>
                        <option value="credit_card" {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>💳 Thẻ tín dụng</option>
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

    <!-- Alert container -->
    <div id="alert-container"></div>

    <!-- Bảng đơn hàng -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                Danh Sách Đơn Hàng ({{ $orders->total() }} đơn)
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã ĐH</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tài xế</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày đặt</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thanh toán</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 {{ $order->status == 'ready' && !$order->driver_id ? 'bg-red-50' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->customer_email }}</div>
                            <div class="text-sm text-gray-500">{{ $order->customer_phone }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->driver)
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-xs font-medium text-blue-600">{{ substr($order->driver->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->driver->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->driver->vehicle_number }}</div>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center">
                                    @if($order->status == 'ready')
                                        <span class="text-red-600 font-medium text-sm">🚨 Cần gán tài xế</span>
                                    @else
                                        <span class="text-gray-500 text-sm">Chưa gán</span>
                                    @endif
                                </div>
                            @endif
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
                                    'assigned' => ['class' => 'bg-cyan-100 text-cyan-800', 'text' => 'Đã gán tài xế'],
                                    'picked_up' => ['class' => 'bg-orange-100 text-orange-800', 'text' => 'Đã lấy hàng'],
                                    'delivering' => ['class' => 'bg-pink-100 text-pink-800', 'text' => 'Đang giao'],
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
                                    'cod' => ['class' => 'bg-orange-100 text-orange-800', 'text' => 'COD', 'icon' => '💵'],
                                    'bank_transfer' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Chuyển khoản', 'icon' => '🏦'],
                                    'credit_card' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Thẻ tín dụng', 'icon' => '💳']
                                ];
                                $payment = $paymentConfig[$order->payment_method ?? 'cod'] ?? ['class' => 'bg-gray-100 text-gray-800', 'text' => 'Không xác định', 'icon' => '❓'];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payment['class'] }}">
                                <span class="mr-1">{{ $payment['icon'] }}</span>
                                {{ $payment['text'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <!-- Nút xem chi tiết (luôn có) -->
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="text-blue-600 hover:text-blue-900" title="Xem chi tiết">👁️</a>

                                @if($order->status === 'pending')
                                    <button onclick="updateStatus({{ $order->id }}, 'confirmed')"
                                            class="text-green-600 hover:text-green-900" title="Xác nhận đơn">✅</button>
                                    <button onclick="updateStatus({{ $order->id }}, 'cancelled')"
                                            class="text-red-600 hover:text-red-900" title="Hủy đơn">❌</button>

                                @elseif($order->status === 'confirmed')
                                    <button onclick="updateStatus({{ $order->id }}, 'preparing')"
                                            class="text-purple-600 hover:text-purple-900" title="Bắt đầu chuẩn bị">👨‍🍳</button>
                                    <button onclick="updateStatus({{ $order->id }}, 'cancelled')"
                                            class="text-red-600 hover:text-red-900" title="Hủy đơn">❌</button>

                                @elseif($order->status === 'preparing')
                                    <button onclick="updateStatus({{ $order->id }}, 'ready')"
                                            class="text-blue-600 hover:text-blue-900" title="Sẵn sàng giao">📦</button>
                                    <button onclick="updateStatus({{ $order->id }}, 'cancelled')"
                                            class="text-red-600 hover:text-red-900" title="Hủy đơn">❌</button>

                                @elseif($order->status === 'ready')
                                    @if(!$order->driver_id)
                                        <button onclick="showDriverModal({{ $order->id }})"
                                                class="text-purple-600 hover:text-purple-900" title="Gán tài xế">🚚</button>
                                    @else
                                        <button onclick="updateDeliveryStatus({{ $order->id }}, 'picked_up')"
                                                class="text-orange-600 hover:text-orange-900" title="Đã lấy hàng">📋</button>
                                        <button onclick="unassignDriver({{ $order->id }})"
                                                class="text-gray-600 hover:text-gray-900" title="Hủy gán tài xế">🔄</button>
                                    @endif
                                    <button onclick="updateStatus({{ $order->id }}, 'cancelled')"
                                            class="text-red-600 hover:text-red-900" title="Hủy đơn">❌</button>

                                @elseif($order->status === 'assigned')
                                    <button onclick="updateDeliveryStatus({{ $order->id }}, 'picked_up')"
                                            class="text-orange-600 hover:text-orange-900" title="Đã lấy hàng">📋</button>
                                    <button onclick="unassignDriver({{ $order->id }})"
                                            class="text-gray-600 hover:text-gray-900" title="Hủy gán tài xế">🔄</button>
                                    <button onclick="updateStatus({{ $order->id }}, 'cancelled')"
                                            class="text-red-600 hover:text-red-900" title="Hủy đơn">❌</button>

                                @elseif($order->status === 'picked_up')
                                    <button onclick="updateDeliveryStatus({{ $order->id }}, 'delivering')"
                                            class="text-blue-600 hover:text-blue-900" title="Đang giao">🚛</button>
                                    <button onclick="updateDeliveryStatus({{ $order->id }}, 'delivered')"
                                            class="text-green-600 hover:text-green-900" title="Đã giao xong">✅</button>

                                @elseif($order->status === 'delivering')
                                    <button onclick="updateDeliveryStatus({{ $order->id }}, 'delivered')"
                                            class="text-green-600 hover:text-green-900" title="Đã giao xong">✅</button>

                                @elseif($order->status === 'delivered')
                                    <span class="text-green-600 text-xs">✅ Hoàn thành</span>

                                @elseif($order->status === 'cancelled')
                                    <button onclick="updateStatus({{ $order->id }}, 'pending')"
                                            class="text-blue-600 hover:text-blue-900" title="Khôi phục đơn">🔄</button>
                                    <span class="text-red-600 text-xs">❌ Đã hủy</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
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

    <!-- Driver Assignment Modal -->
    <div id="driverModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">🚚 Gán Tài Xế</h3>
                    <button onclick="closeDriverModal()" class="text-gray-400 hover:text-gray-600">
                        <span class="sr-only">Đóng</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="assignDriverForm" method="POST" onsubmit="submitDriverAssignment(event)">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Chọn tài xế:</label>
                        <select name="driver_id" id="driverSelect" class="w-full border border-gray-300 rounded px-3 py-2" required>
                            <option value="">-- Chọn tài xế --</option>
                        </select>
                        <div id="driverInfo" class="mt-2 text-sm text-gray-600"></div>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeDriverModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Hủy
                        </button>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Gán Tài Xế
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let currentOrderId = null;
        let availableDrivers = [];

        // Load available drivers on page load
        document.addEventListener('DOMContentLoaded', function() {
            fetchAvailableDrivers();
        });

        function fetchAvailableDrivers() {
            fetch('/admin/drivers/available')
                .then(response => response.json())
                .then(data => {
                    availableDrivers = data;
                })
                .catch(error => console.error('Error fetching drivers:', error));
        }

        // AJAX Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Main status update function
        function updateStatus(orderId, status) {
            const confirmMessages = {
                'confirmed': 'Xác nhận đơn hàng này?',
                'preparing': 'Bắt đầu chuẩn bị đơn hàng?',
                'ready': 'Đánh dấu sẵn sàng giao hàng?',
                'cancelled': '⚠️ Bạn chắc chắn muốn HỦY đơn hàng này?',
                'pending': 'Khôi phục đơn hàng này?'
            };

            if (confirm(confirmMessages[status] || 'Xác nhận thay đổi?')) {
                $.ajax({
                    url: `/admin/orders/${orderId}/status`,
                    type: 'PATCH',
                    data: { status: status },
                    success: function(response) {
                        if (response.success) {
                            showAlert('success', response.message);
                            setTimeout(() => location.reload(), 1500);
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'Có lỗi xảy ra!';
                        showAlert('error', message);
                    }
                });
            }
        }

        // Delivery status update function
        // Enhanced JavaScript với debug logging
        function updateDeliveryStatus(orderId, status) {
            console.log('updateDeliveryStatus called:', {orderId, status});

            const confirmMessages = {
                'picked_up': 'Xác nhận đã lấy hàng?',
                'delivering': 'Đánh dấu đang giao hàng?',
                'delivered': '✅ Xác nhận đã giao hàng thành công?'
            };

            if (confirm(confirmMessages[status] || 'Xác nhận?')) {
                const notes = status === 'delivered' ? prompt('Ghi chú (tùy chọn):') : null;

                console.log('Sending AJAX request:', {
                    url: `/admin/orders/${orderId}/delivery-status`,
                    data: { status, notes }
                });

                $.ajax({
                    url: `/admin/orders/${orderId}/delivery-status`,
                    type: 'PATCH',
                    data: {
                        status: status,
                        notes: notes,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function(xhr) {
                        console.log('Request headers:', xhr.getAllResponseHeaders());
                        console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
                    },
                    success: function(response) {
                        console.log('Success response:', response);
                        if (response.success) {
                            showAlert('success', response.message);
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showAlert('error', response.message || 'Có lỗi xảy ra');
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.error('AJAX Error Details:', {
                            status: xhr.status,
                            statusText: xhr.statusText,
                            responseText: xhr.responseText,
                            textStatus: textStatus,
                            errorThrown: errorThrown
                        });

                        let message = 'Có lỗi xảy ra!';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        } else if (xhr.status === 404) {
                            message = 'Không tìm thấy route. Kiểm tra lại routes/web.php';
                        } else if (xhr.status === 419) {
                            message = 'CSRF Token expired. Làm mới trang.';
                        } else if (xhr.status === 500) {
                            message = 'Lỗi server. Kiểm tra logs.';
                        }

                        showAlert('error', message);
                    }
                });
            }
        }

        // Test function để kiểm tra routes
        function testRoutes() {
            console.log('Testing routes...');

            // Test route existence
            $.get('/admin/orders/1/delivery-status', function() {
                console.log('Route exists');
            }).fail(function(xhr) {
                if (xhr.status === 405) {
                    console.log('Route exists but method not allowed (expected for GET)');
                } else {
                    console.error('Route does not exist:', xhr.status);
                }
            });
        }

        // Unassign driver function
        function unassignDriver(orderId) {
            if (confirm('🔄 Hủy gán tài xế cho đơn hàng này?')) {
                $.ajax({
                    url: `/admin/orders/${orderId}/unassign-driver`,
                    type: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            showAlert('success', response.message);
                            setTimeout(() => location.reload(), 1500);
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'Có lỗi xảy ra!';
                        showAlert('error', message);
                    }
                });
            }
        }

        // Auto assign drivers function
        function autoAssignDrivers() {
            if (confirm('Tự động gán tài xế cho tất cả đơn hàng sẵn sàng?')) {
                $.ajax({
                    url: '/admin/orders/auto-assign-drivers',
                    type: 'POST',
                    success: function(response) {
                        if (response.success) {
                            showAlert('success', response.message);
                            setTimeout(() => location.reload(), 2000);
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'Có lỗi xảy ra!';
                        showAlert('error', message);
                    }
                });
            }
        }

        // Driver modal functions
        function showDriverModal(orderId) {
            currentOrderId = orderId;
            const modal = document.getElementById('driverModal');
            const form = document.getElementById('assignDriverForm');
            const select = document.getElementById('driverSelect');

            form.action = `/admin/orders/${orderId}/assign-driver`;
            select.innerHTML = '<option value="">-- Chọn tài xế --</option>';

            availableDrivers.forEach(driver => {
                const option = document.createElement('option');
                option.value = driver.id;
                option.textContent = `${driver.driver_code} - ${driver.name} (${driver.current_orders_count || 0}/3 đơn)`;
                option.dataset.driverInfo = JSON.stringify(driver);
                select.appendChild(option);
            });

            modal.classList.remove('hidden');
        }

        function closeDriverModal() {
            document.getElementById('driverModal').classList.add('hidden');
            currentOrderId = null;
        }

        function submitDriverAssignment(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const driverId = formData.get('driver_id');

            if (!driverId) {
                showAlert('error', 'Vui lòng chọn tài xế!');
                return;
            }

            $.ajax({
                url: `/admin/orders/${currentOrderId}/assign-driver`,
                type: 'POST',
                data: { driver_id: driverId },
                success: function(response) {
                    if (response.success) {
                        closeDriverModal();
                        showAlert('success', response.message);
                        setTimeout(() => location.reload(), 1500);
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'Có lỗi xảy ra!';
                    showAlert('error', message);
                }
            });
        }

        // Show driver info when selected
        document.getElementById('driverSelect').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const infoDiv = document.getElementById('driverInfo');

            if (selectedOption.dataset.driverInfo) {
                const driver = JSON.parse(selectedOption.dataset.driverInfo);
                infoDiv.innerHTML = `
                    <div class="bg-blue-50 p-2 rounded">
                        <p><strong>Loại xe:</strong> ${getVehicleTypeName(driver.vehicle_type)}</p>
                        <p><strong>Đang giao:</strong> ${driver.current_orders_count || 0}/3 đơn</p>
                        <p><strong>Trạng thái:</strong> ${getStatusName(driver.status)}</p>
                    </div>
                `;
            } else {
                infoDiv.innerHTML = '';
            }
        });

        // Helper functions
        function getVehicleTypeName(type) {
            const types = {
                'motorbike': '🏍️ Xe máy',
                'small_truck': '🚚 Xe tải nhỏ',
                'van': '🚐 Xe van'
            };
            return types[type] || type;
        }

        function getStatusName(status) {
            const statuses = {
                'active': '✅ Sẵn sàng',
                'busy': '🚚 Đang bận',
                'inactive': '⏸️ Ngừng hoạt động'
            };
            return statuses[status] || status;
        }

        // Alert function
        function showAlert(type, message) {
            const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
            const icon = type === 'success' ? '✅' : '❌';

            const alert = $(`
                <div class="${alertClass} border px-4 py-3 rounded mb-4" role="alert">
                    <span class="block sm:inline">${icon} ${message}</span>
                </div>
            `);

            $('#alert-container').html(alert);

            setTimeout(() => {
                alert.fadeOut(500, function() {
                    $(this).remove();
                });
            }, 5000);
        }

        // Refresh drivers list periodically
        setInterval(fetchAvailableDrivers, 30000);
    </script>
</x-admin-layout>
