<x-admin-layout>
    <x-slot name="header">
        Chi Tiết Đơn Hàng #{{ $order->order_number ?? $order->id }} 📋
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
            <a href="{{ route('admin.orders.index') }}"
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Quay lại
            </a>
            <a href="{{ route('admin.orders.edit', $order) }}"
               class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                ✏️ Chỉnh sửa
            </a>
            <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                🖨️ In hóa đơn
            </a>
            @if($order->driver)
                <button onclick="showRouteOptimization({{ $order->driver_id }})"
                        class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                    🗺️ Tối ưu tuyến đường
                </button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Cột chính -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Order Timeline -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">🚀 Tiến trình đơn hàng</h3>
                </div>
                <div class="p-6">
                    @php
                        $statuses = [
                            'pending' => ['icon' => '⏳', 'name' => 'Chờ xác nhận', 'color' => 'yellow'],
                            'confirmed' => ['icon' => '✅', 'name' => 'Đã xác nhận', 'color' => 'blue'],
                            'preparing' => ['icon' => '👨‍🍳', 'name' => 'Đang chuẩn bị', 'color' => 'purple'],
                            'ready' => ['icon' => '📦', 'name' => 'Sẵn sàng giao', 'color' => 'indigo'],
                            'assigned' => ['icon' => '🚚', 'name' => 'Đã gán tài xế', 'color' => 'cyan'],
                            'picked_up' => ['icon' => '📋', 'name' => 'Đã lấy hàng', 'color' => 'orange'],
                            'delivering' => ['icon' => '🚛', 'name' => 'Đang giao', 'color' => 'pink'],
                            'delivered' => ['icon' => '🎉', 'name' => 'Đã giao', 'color' => 'green'],
                        ];

                        $currentStatusIndex = array_search($order->status, array_keys($statuses));
                        if ($order->status === 'cancelled') {
                            $currentStatusIndex = -1;
                        }
                    @endphp

                    @if($order->status === 'cancelled')
                        <div class="flex items-center justify-center p-6 bg-red-50 border border-red-200 rounded">
                            <span class="text-3xl mr-3">❌</span>
                            <div>
                                <h4 class="text-lg font-bold text-red-800">Đơn hàng đã bị hủy</h4>
                                <p class="text-red-600">Hủy lúc: {{ $order->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center justify-between">
                            @foreach($statuses as $key => $status)
                                @php
                                    $index = array_search($key, array_keys($statuses));
                                    $isCompleted = $index <= $currentStatusIndex;
                                    $isCurrent = $key === $order->status;
                                @endphp
                                <div class="flex flex-col items-center relative {{ $index > 0 ? 'flex-1' : '' }}">
                                    @if($index > 0)
                                        <div class="absolute top-5 left-0 w-full h-1 {{ $isCompleted ? 'bg-green-400' : 'bg-gray-300' }} -z-10"></div>
                                    @endif
                                    <div class="w-10 h-10 rounded-full border-2 flex items-center justify-center text-lg z-10
                                        {{ $isCompleted ? 'bg-green-100 border-green-400 text-green-600' : 'bg-gray-100 border-gray-300 text-gray-400' }}
                                        {{ $isCurrent ? 'ring-4 ring-green-200' : '' }}">
                                        {{ $status['icon'] }}
                                    </div>
                                    <p class="text-xs text-center mt-2 max-w-20 {{ $isCompleted ? 'text-green-600 font-medium' : 'text-gray-500' }}">
                                        {{ $status['name'] }}
                                    </p>
                                    @if($isCurrent)
                                        <p class="text-xs text-green-600 font-bold mt-1">Hiện tại</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Thông tin đơn hàng -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">📋 Thông tin đơn hàng</h3>
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
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">🔢 Mã đơn hàng:</p>
                                <p class="font-medium">#{{ $order->order_number ?? $order->id }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">📅 Ngày tạo:</p>
                                <p class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">💳 Phương thức thanh toán:</p>
                                <p class="font-medium">
                                    @switch($order->payment_method)
                                        @case('cod') 💵 COD @break
                                        @case('bank_transfer') 🏦 Chuyển khoản @break
                                        @case('credit_card') 💳 Thẻ tín dụng @break
                                        @default {{ $order->payment_method ?? 'COD' }}
                                    @endswitch
                                </p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">🔄 Cập nhật cuối:</p>
                                <p class="font-medium">{{ $order->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">💰 Tổng tiền:</p>
                                <p class="text-2xl font-bold text-blue-600">{{ number_format($order->total_amount) }}₫</p>
                            </div>
                            @if($order->delivered_at)
                                <div>
                                    <p class="text-sm text-gray-600">🚚 Thời gian giao:</p>
                                    <p class="font-medium">{{ $order->delivered_at->format('d/m/Y H:i') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin tài xế -->
            @if($order->driver)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">🚚 Thông tin tài xế</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 h-16 w-16">
                                <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-2xl font-bold text-blue-600">{{ substr($order->driver->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="text-lg font-bold text-gray-900">{{ $order->driver->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $order->driver->driver_code }}</p>
                                        <p class="text-sm text-gray-600">📞 {{ $order->driver->phone }}</p>
                                        <p class="text-sm text-gray-600">⭐ {{ $order->driver->formatted_rating }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">🚗 {{ $order->driver->vehicle_type_name }}</p>
                                        <p class="text-sm text-gray-600">🔢 {{ $order->driver->vehicle_number }}</p>
                                        <p class="text-sm text-gray-600">📦 {{ $order->driver->current_orders_count }}/3 đơn hiện tại</p>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            {{ $order->driver->status === 'active' ? 'bg-green-100 text-green-800' :
                                               ($order->driver->status === 'busy' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $order->driver->status_name }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                @if(in_array($order->status, ['ready', 'assigned']) && $order->driver)
                                    <button onclick="unassignDriver({{ $order->id }})"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                                        🔄 Hủy gán
                                    </button>
                                @endif
                            </div>
                        </div>

                        @if($order->assigned_at)
                            <div class="mt-4 p-4 bg-blue-50 rounded">
                                <p class="text-sm"><strong>Gán lúc:</strong> {{ $order->assigned_at->format('d/m/Y H:i') }}</p>
                                @if($order->picked_up_at)
                                    <p class="text-sm"><strong>Lấy hàng lúc:</strong> {{ $order->picked_up_at->format('d/m/Y H:i') }}</p>
                                @endif
                                @if($order->delivery_notes)
                                    <p class="text-sm"><strong>Ghi chú giao hàng:</strong> {{ $order->delivery_notes }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @elseif($order->status === 'ready')
                <div class="bg-white rounded-lg shadow overflow-hidden border-l-4 border-red-500">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-red-600">🚨 Cần gán tài xế</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">Đơn hàng đã sẵn sàng giao nhưng chưa có tài xế. Hãy gán tài xế phù hợp.</p>
                        <button onclick="showDriverAssignmentModal({{ $order->id }})"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            🚚 Gán tài xế
                        </button>
                        <button onclick="autoAssignDriver({{ $order->id }})"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded ml-2">
                            🤖 Tự động gán
                        </button>
                    </div>
                </div>
            @endif

            <!-- Thông tin khách hàng -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">👤 Thông tin khách hàng</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">👤 Tên khách hàng:</p>
                                <p class="font-medium">{{ $order->customer_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">📞 Số điện thoại:</p>
                                <p class="font-medium">
                                    <a href="tel:{{ $order->customer_phone }}" class="text-blue-600 hover:underline">
                                        {{ $order->customer_phone }}
                                    </a>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">✉️ Email:</p>
                                <p class="font-medium">{{ $order->customer_email ?: 'Không có' }}</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">📍 Địa chỉ giao hàng:</p>
                                <p class="font-medium">{{ $order->customer_address }}</p>
                                <a href="https://maps.google.com/?q={{ urlencode($order->customer_address) }}"
                                   target="_blank"
                                   class="text-blue-600 hover:underline text-sm">
                                    🗺️ Xem trên bản đồ
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chi tiết sản phẩm -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">🛒 Chi tiết sản phẩm</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số lượng</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn giá</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thành tiền</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @php $subtotal = 0; @endphp
                        @foreach($order->orderItems as $item)
                            @php
                                $itemTotal = $item->quantity * $item->price;
                                $subtotal += $itemTotal;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                 alt="{{ $item->product->name }}"
                                                 class="w-12 h-12 object-cover rounded mr-3">
                                        @else
                                            <div class="w-12 h-12 bg-gray-100 rounded mr-3 flex items-center justify-center text-lg">
                                                🧁
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $item->product ? $item->product->name : 'Sản phẩm đã xóa' }}
                                            </div>
                                            @if($item->product && $item->product->description)
                                                <div class="text-sm text-gray-500">
                                                    {{ Str::limit($item->product->description, 50) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->quantity }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($item->price) }}₫
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ number_format($itemTotal) }}₫
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Tổng kết -->
                <div class="bg-gray-50 px-6 py-4">
                    <div class="flex justify-end">
                        <div class="text-right space-y-2 min-w-[300px]">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">💳 Tạm tính:</span>
                                <span class="text-sm font-medium">{{ number_format($subtotal) }}₫</span>
                            </div>
                            @php $shipping = $order->total_amount - $subtotal; @endphp
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">🚚 Phí vận chuyển:</span>
                                <span class="text-sm font-medium">{{ $shipping > 0 ? number_format($shipping) . '₫' : 'Miễn phí' }}</span>
                            </div>
                            <div class="border-t pt-2 flex justify-between">
                                <span class="text-lg font-bold">💰 Tổng cộng:</span>
                                <span class="text-lg font-bold text-blue-600">{{ number_format($order->total_amount) }}₫</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ghi chú -->
            @if($order->notes)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">💬 Ghi chú</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700">{{ $order->notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">⚡ Hành động nhanh</h3>
                </div>
                <div class="p-6 space-y-3">
                    @if($order->status !== 'delivered' && $order->status !== 'cancelled')
                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cập nhật trạng thái:</label>
                                <select name="status" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" required>
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>⏳ Chờ xác nhận</option>
                                    <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>✅ Đã xác nhận</option>
                                    <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>👨‍🍳 Đang chuẩn bị</option>
                                    <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>📦 Sẵn sàng giao</option>
                                    <option value="assigned" {{ $order->status == 'assigned' ? 'selected' : '' }}>🚚 Đã gán tài xế</option>
                                    <option value="picked_up" {{ $order->status == 'picked_up' ? 'selected' : '' }}>📋 Đã lấy hàng</option>
                                    <option value="delivering" {{ $order->status == 'delivering' ? 'selected' : '' }}>🚛 Đang giao</option>
                                    <option value="delivere
