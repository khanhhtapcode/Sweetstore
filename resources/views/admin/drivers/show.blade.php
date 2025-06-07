<x-admin-layout>
    <x-slot name="header">
        Chi Tiết Tài Xế: {{ $driver->name }} 🚗
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

    <!-- Nút hành động nhanh -->
    <div class="mb-6">
        <div class="flex space-x-4">
            <a href="{{ route('admin.drivers.index') }}"
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Quay lại
            </a>
            <a href="{{ route('admin.drivers.edit', $driver) }}"
               class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                ✏️ Chỉnh sửa
            </a>
            @if($driver->status === 'active')
                <button onclick="updateDriverStatus('{{ $driver->id }}', 'inactive')"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    ⏸️ Ngừng hoạt động
                </button>
            @else
                <button onclick="updateDriverStatus('{{ $driver->id }}', 'active')"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    ✅ Kích hoạt
                </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Cột chính -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Thông tin cơ bản -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">👤 Thông tin tài xế</h3>
                    @php
                        $statusConfig = [
                            'active' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Sẵn sàng'],
                            'busy' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Đang bận'],
                            'inactive' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Ngừng hoạt động']
                        ];
                        $config = $statusConfig[$driver->status] ?? $statusConfig['inactive'];
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['class'] }}">
                        {{ $config['text'] }}
                    </span>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">🔢 Mã tài xế:</p>
                                <p class="font-medium">{{ $driver->driver_code }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">👤 Họ và tên:</p>
                                <p class="font-medium">{{ $driver->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">✉️ Email:</p>
                                <p class="font-medium">{{ $driver->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">📞 Số điện thoại:</p>
                                <p class="font-medium">{{ $driver->phone }}</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">📍 Địa chỉ:</p>
                                <p class="font-medium">{{ $driver->address }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">⭐ Đánh giá:</p>
                                <p class="text-2xl font-bold text-yellow-600">{{ $driver->formatted_rating }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">🚚 Tổng đơn đã giao:</p>
                                <p class="text-xl font-bold text-blue-600">{{ number_format($driver->total_deliveries) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">⏰ Hoạt động cuối:</p>
                                <p class="font-medium">{{ $driver->last_active_at ? $driver->last_active_at->diffForHumans() : 'Chưa có' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin phương tiện -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">🚗 Thông tin phương tiện & bằng lái</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">🚗 Loại xe:</p>
                                <p class="font-medium">{{ $driver->vehicle_type_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">🔢 Biển số xe:</p>
                                <p class="font-medium text-lg">{{ $driver->vehicle_number }}</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">📋 Số bằng lái:</p>
                                <p class="font-medium">{{ $driver->license_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">📅 Hạn bằng lái:</p>
                                <div class="flex items-center space-x-2">
                                    <p class="font-medium">{{ $driver->license_expiry->format('d/m/Y') }}</p>
                                    @if($driver->is_license_expired)
                                        <span class="text-red-600 font-bold">❌ Đã hết hạn</span>
                                    @elseif($driver->is_license_expiring_soon)
                                        <span class="text-yellow-600 font-bold">⚠️ Sắp hết hạn</span>
                                    @else
                                        <span class="text-green-600">✅ Còn hiệu lực</span>
                                    @endif
                                </div>
                                @if($driver->days_to_license_expiry !== null)
                                    <p class="text-xs text-gray-500">
                                        @if($driver->is_license_expired)
                                            Đã hết hạn {{ abs($driver->days_to_license_expiry) }} ngày
                                        @else
                                            Còn {{ $driver->days_to_license_expiry }} ngày
                                        @endif
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Đơn hàng hiện tại -->
            @if($driver->currentOrders->count() > 0)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">🚚 Đơn hàng đang giao ({{ $driver->currentOrders->count() }})</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn hàng</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($driver->currentOrders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->customer_phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $orderStatusConfig = [
                                                'assigned' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Đã gán'],
                                                'picked_up' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Đã lấy hàng'],
                                                'delivering' => ['class' => 'bg-purple-100 text-purple-800', 'text' => 'Đang giao']
                                            ];
                                            $orderConfig = $orderStatusConfig[$order->status] ?? ['class' => 'bg-gray-100 text-gray-800', 'text' => $order->status];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $orderConfig['class'] }}">
                                            {{ $orderConfig['text'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-blue-600">{{ number_format($order->total_amount) }}₫</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                           class="text-blue-600 hover:text-blue-900">👁️ Xem</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Lịch sử đơn hàng -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">📋 Lịch sử đơn hàng ({{ $driver->orders->count() }} đơn)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn hàng</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($driver->orders->take(10) as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-900">
                                            #{{ $order->order_number }}
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->customer_phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $order->status_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-blue-600">{{ number_format($order->total_amount) }}₫</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $order->created_at->format('d/m/Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <div class="text-2xl mb-2">📦</div>
                                    <div>Chưa có đơn hàng nào</div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @if($driver->orders->count() > 10)
                    <div class="px-6 py-3 bg-gray-50 text-center">
                        <a href="{{ route('admin.orders.index', ['driver_id' => $driver->id]) }}" class="text-blue-600 hover:text-blue-900">
                            Xem tất cả {{ $driver->orders->count() }} đơn hàng →
                        </a>
                    </div>
                @endif
            </div>

            <!-- Ghi chú -->
            @if($driver->notes)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">💬 Ghi chú</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700">{{ $driver->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Cột bên -->
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
                                <p class="text-xl font-bold text-blue-600">{{ $stats['total_orders'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                        <div class="flex items-center">
                            <div class="text-green-600 text-2xl mr-3">✅</div>
                            <div>
                                <p class="text-sm text-gray-600">Đã hoàn thành</p>
                                <p class="text-xl font-bold text-green-600">{{ $stats['completed_orders'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                        <div class="flex items-center">
                            <div class="text-yellow-600 text-2xl mr-3">🚚</div>
                            <div>
                                <p class="text-sm text-gray-600">Đang giao</p>
                                <p class="text-xl font-bold text-yellow-600">{{ $stats['current_orders'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                        <div class="flex items-center">
                            <div class="text-purple-600 text-2xl mr-3">💰</div>
                            <div>
                                <p class="text-sm text-gray-600">Tổng doanh thu</p>
                                <p class="text-xl font-bold text-purple-600">{{ number_format($stats['total_revenue']) }}₫</p>
                            </div>
                        </div>
                    </div>

                    @if($stats['average_delivery_time'])
                        <div class="bg-indigo-50 rounded-lg p-4 border border-indigo-200">
                            <div class="flex items-center">
                                <div class="text-indigo-600 text-2xl mr-3">⏱️</div>
                                <div>
                                    <p class="text-sm text-gray-600">Thời gian giao TB</p>
                                    <p class="text-xl font-bold text-indigo-600">{{ round($stats['average_delivery_time']) }} phút</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Hiệu suất -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">🎯 Hiệu suất</h3>
                </div>
                <div class="p-6 space-y-4">
                    @if($stats['completed_orders'] > 0)
                        @php
                            $completionRate = ($stats['completed_orders'] / $stats['total_orders']) * 100;
                            $onTimeRate = ($stats['on_time_deliveries'] / $stats['completed_orders']) * 100;
                        @endphp

                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Tỷ lệ hoàn thành</span>
                                <span class="text-sm font-bold">{{ round($completionRate, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $completionRate }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Giao đúng hạn</span>
                                <span class="text-sm font-bold">{{ round($onTimeRate, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $onTimeRate }}%"></div>
                            </div>
                        </div>
                    @else
                        <div class="text-center text-gray-500">
                            <div class="text-2xl mb-2">📈</div>
                            <div class="text-sm">Chưa có dữ liệu hiệu suất</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Thông tin thời gian -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">⏰ Thông tin thời gian</h3>
                </div>
                <div class="p-6 space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Ngày tham gia:</p>
                        <p class="font-medium">{{ $driver->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Cập nhật cuối:</p>
                        <p class="font-medium">{{ $driver->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Hoạt động cuối:</p>
                        <p class="font-medium">{{ $driver->last_active_at ? $driver->last_active_at->diffForHumans() : 'Chưa có dữ liệu' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateDriverStatus(driverId, status) {
            const statusTexts = {
                'active': 'kích hoạt',
                'inactive': 'ngừng hoạt động',
                'busy': 'đánh dấu bận'
            };

            if (confirm(`Bạn có chắc chắn muốn ${statusTexts[status]} tài xế này?`)) {
                fetch(`/admin/drivers/${driverId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: status })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Lỗi: ' + data.error);
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
