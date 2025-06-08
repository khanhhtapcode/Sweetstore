<x-admin-layout>
    <x-slot name="header">
        Chi Tiết Tài Xế - {{ $driver->name }} ({{ $driver->driver_code }}) 🚚
    </x-slot>

    <!-- Thông báo -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            ❌ {{ session('error') }}
        </div>
    @endif

    <!-- Header Actions -->
    <div class="mb-6">
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.drivers.index') }}"
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Quay lại danh sách
            </a>
            <a href="{{ route('admin.drivers.edit', $driver) }}"
               class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                ✏️ Chỉnh sửa
            </a>
            <button onclick="toggleDriverStatus('{{ $driver->id }}', '{{ $driver->status }}')"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                🔄 Đổi trạng thái
            </button>
            @if($driver->current_orders_count > 0)
                <a href="{{ route('admin.orders.optimize-route', $driver) }}"
                   class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                    🗺️ Tối ưu tuyến đường
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Cột chính -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Thông tin tài xế -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">👨‍💼 Thông tin tài xế</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $driver->status === 'active' ? 'bg-green-100 text-green-800' :
                           ($driver->status === 'busy' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ $driver->status_name }}
                    </span>
                </div>
                <div class="p-6">
                    <div class="flex items-start space-x-6">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            <div class="h-24 w-24 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-3xl font-bold text-blue-600">{{ substr($driver->name, 0, 1) }}</span>
                            </div>
                        </div>

                        <!-- Thông tin cơ bản -->
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-600">👤 Họ tên:</p>
                                    <p class="font-medium text-lg">{{ $driver->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">🏷️ Mã tài xế:</p>
                                    <p class="font-medium">{{ $driver->driver_code }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">📞 Số điện thoại:</p>
                                    <p class="font-medium">
                                        <a href="tel:{{ $driver->phone }}" class="text-blue-600 hover:underline">
                                            {{ $driver->phone }}
                                        </a>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">✉️ Email:</p>
                                    <p class="font-medium">{{ $driver->email }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">📍 Địa chỉ:</p>
                                    <p class="font-medium">{{ $driver->address }}</p>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-600">🚗 Loại xe:</p>
                                    <p class="font-medium">{{ $driver->vehicle_type_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">🔢 Biển số xe:</p>
                                    <p class="font-medium">{{ $driver->vehicle_number }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">📄 Số bằng lái:</p>
                                    <p class="font-medium">{{ $driver->license_number }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">📅 Hạn bằng lái:</p>
                                    <p class="font-medium {{ $driver->is_license_expired ? 'text-red-600' : ($driver->is_license_expiring_soon ? 'text-yellow-600' : '') }}">
                                        {{ $driver->license_expiry ? $driver->license_expiry->format('d/m/Y') : 'Chưa cập nhật' }}
                                        @if($driver->is_license_expired)
                                            <span class="text-red-600 font-bold">(Đã hết hạn)</span>
                                        @elseif($driver->is_license_expiring_soon)
                                            <span class="text-yellow-600 font-bold">(Sắp hết hạn)</span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">⭐ Đánh giá:</p>
                                    <p class="font-medium">{{ $driver->formatted_rating }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($driver->notes)
                        <div class="mt-6 p-4 bg-gray-50 rounded">
                            <p class="text-sm font-medium text-gray-700 mb-2">💬 Ghi chú:</p>
                            <p class="text-gray-700">{{ $driver->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Đơn hàng hiện tại -->
            @if($driver->currentOrders->count() > 0)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">📦 Đơn hàng hiện tại ({{ $driver->currentOrders->count() }})</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn hàng</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Địa chỉ</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($driver->currentOrders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</div>
                                            <div class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $order->customer_phone }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs truncate">{{ $order->customer_address }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $order->status === 'assigned' ? 'bg-cyan-100 text-cyan-800' :
                                               ($order->status === 'picked_up' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800') }}">
                                            {{ $order->status_name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ number_format($order->total_amount) }}₫
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                           class="text-blue-600 hover:text-blue-900">Xem chi tiết</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-green-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="text-green-600 text-3xl mr-4">✅</div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Tài xế đang rảnh</h3>
                                <p class="text-gray-600">Hiện tại không có đơn hàng nào được gán cho tài xế này.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Lịch sử đơn hàng -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">📋 Lịch sử đơn hàng gần đây</h3>
                </div>
                @if($driver->orders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn hàng</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày giao</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($driver->orders->take(10) as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</div>
                                            <div class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $order->customer_phone }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' :
                                               ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ $order->status_name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ number_format($order->total_amount) }}₫
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $order->delivered_at ? $order->delivered_at->format('d/m/Y H:i') : 'Chưa giao' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                           class="text-blue-600 hover:text-blue-900">Xem chi tiết</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-6 text-center text-gray-500">
                        <div class="text-4xl mb-4">📭</div>
                        <p>Chưa có đơn hàng nào</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Thống kê -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">📊 Thống kê</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <div class="flex items-center">
                            <div class="text-blue-600 text-2xl mr-3">📦</div>
                            <div>
                                <p class="text-sm text-gray-600">Tổng đơn hàng</p>
                                <p class="text-xl font-bold text-blue-600">{{ $stats['total_orders'] ?? $driver->orders->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                        <div class="flex items-center">
                            <div class="text-green-600 text-2xl mr-3">✅</div>
                            <div>
                                <p class="text-sm text-gray-600">Đã hoàn thành</p>
                                <p class="text-xl font-bold text-green-600">{{ $stats['completed_orders'] ?? $driver->completedOrders->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                        <div class="flex items-center">
                            <div class="text-yellow-600 text-2xl mr-3">🚚</div>
                            <div>
                                <p class="text-sm text-gray-600">Đang giao</p>
                                <p class="text-xl font-bold text-yellow-600">{{ $stats['current_orders'] ?? $driver->currentOrders->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                        <div class="flex items-center">
                            <div class="text-purple-600 text-2xl mr-3">💰</div>
                            <div>
                                <p class="text-sm text-gray-600">Tổng doanh thu</p>
                                <p class="text-xl font-bold text-purple-600">{{ number_format($stats['total_revenue'] ?? 0) }}₫</p>
                            </div>
                        </div>
                    </div>

                    @if(isset($stats['average_delivery_time']))
                        <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-200">
                            <div class="flex items-center">
                                <div class="text-indigo-600 text-2xl mr-3">⏱️</div>
                                <div>
                                    <p class="text-sm text-gray-600">TG giao TB</p>
                                    <p class="text-xl font-bold text-indigo-600">{{ round($stats['average_delivery_time']) }} phút</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(isset($stats['on_time_deliveries']))
                        <div class="bg-teal-50 rounded-lg p-4 border border-teal-200">
                            <div class="flex items-center">
                                <div class="text-teal-600 text-2xl mr-3">🎯</div>
                                <div>
                                    <p class="text-sm text-gray-600">Giao đúng hạn</p>
                                    <p class="text-xl font-bold text-teal-600">{{ $stats['on_time_deliveries'] ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Hành động nhanh -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">⚡ Hành động nhanh</h3>
                </div>
                <div class="p-6 space-y-3">
                    <!-- Cập nhật trạng thái -->
                    <form action="{{ route('admin.drivers.toggle-status', $driver) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái:</label>
                            <select name="status" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                                <option value="active" {{ $driver->status == 'active' ? 'selected' : '' }}>🟢 Sẵn sàng</option>
                                <option value="busy" {{ $driver->status == 'busy' ? 'selected' : '' }}>🟡 Đang bận</option>
                                <option value="inactive" {{ $driver->status == 'inactive' ? 'selected' : '' }}>🔴 Không hoạt động</option>
                            </select>
                        </div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                            🔄 Cập nhật trạng thái
                        </button>
                    </form>

                    <div class="grid grid-cols-1 gap-3">
                        <a href="{{ route('admin.drivers.edit', $driver) }}"
                           class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-center">
                            ✏️ Chỉnh sửa
                        </a>
                        @if($driver->currentOrders->count() == 0)
                            <button onclick="confirmDelete({{ $driver->id }})"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                🗑️ Xóa tài xế
                            </button>
                        @else
                            <button disabled
                                    class="bg-gray-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed">
                                🚫 Có đơn hàng
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Cảnh báo bằng lái -->
            @if($driver->is_license_expired || $driver->is_license_expiring_soon)
                <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 {{ $driver->is_license_expired ? 'border-red-500' : 'border-yellow-500' }}">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold {{ $driver->is_license_expired ? 'text-red-600' : 'text-yellow-600' }}">
                            ⚠️ Cảnh báo bằng lái
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($driver->is_license_expired)
                            <p class="text-red-600 font-medium mb-2">Bằng lái đã hết hạn!</p>
                            <p class="text-sm text-gray-600">Hết hạn từ {{ $driver->license_expiry->format('d/m/Y') }}</p>
                        @else
                            <p class="text-yellow-600 font-medium mb-2">Bằng lái sắp hết hạn!</p>
                            <p class="text-sm text-gray-600">Còn {{ $driver->days_to_license_expiry }} ngày</p>
                        @endif
                        <p class="text-sm text-gray-500 mt-3">Hãy nhắc nhở tài xế gia hạn bằng lái.</p>
                    </div>
                </div>
            @endif

            <!-- Thông tin hoạt động -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">📅 Hoạt động</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">📅 Ngày tham gia:</p>
                        <p class="font-medium">{{ $driver->created_at->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $driver->created_at->diffForHumans() }}</p>
                    </div>
                    @if($driver->last_active_at)
                        <div>
                            <p class="text-sm text-gray-600">🕐 Hoạt động cuối:</p>
                            <p class="font-medium">{{ $driver->last_active_at->format('d/m/Y H:i') }}</p>
                            <p class="text-xs text-gray-500">{{ $driver->last_active_at->diffForHumans() }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(driverId) {
            if (confirm('❌ Bạn có chắc chắn muốn xóa tài xế này?\n\n⚠️ Hành động này không thể hoàn tác!')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/drivers/${driverId}`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);

                document.body.appendChild(form);
                form.submit();
            }
        }

        function toggleDriverStatus(driverId, currentStatus) {
            const statusOptions = {
                'active': 'busy',
                'busy': 'inactive',
                'inactive': 'active'
            };

            const newStatus = statusOptions[currentStatus];
            const statusNames = {
                'active': 'Sẵn sàng',
                'busy': 'Đang bận',
                'inactive': 'Không hoạt động'
            };

            if (confirm(`Chuyển trạng thái từ "${statusNames[currentStatus]}" sang "${statusNames[newStatus]}"?`)) {
                fetch(`/admin/drivers/${driverId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Có lỗi xảy ra: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi cập nhật trạng thái');
                    });
            }
        }

        // Auto dismiss alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</x-admin-layout>
