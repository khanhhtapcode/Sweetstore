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

    <!-- Nút hành động nhanh -->
    <div class="mb-6">
        <div class="flex space-x-4">
            <a href="{{ route('admin.orders.index') }}"
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Quay lại
            </a>
            <a href="{{ route('admin.orders.edit', $order) }}"
               class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                ✏️ Chỉnh sửa
            </a>
            <button onclick="window.print()"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                🖨️ In đơn hàng
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Cột chính -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Thông tin đơn hàng -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">📋 Thông tin đơn hàng</h3>
                    @php
                        $statusConfig = [
                            'pending' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Chờ xử lý'],
                            'confirmed' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Đã xác nhận'],
                            'preparing' => ['class' => 'bg-purple-100 text-purple-800', 'text' => 'Đang chuẩn bị'],
                            'delivering' => ['class' => 'bg-indigo-100 text-indigo-800', 'text' => 'Đang giao'],
                            'completed' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Hoàn thành'],
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
                                        @case('cash') 💵 Tiền mặt @break
                                        @case('bank_transfer') 🏦 Chuyển khoản @break
                                        @case('credit_card') 💳 Thẻ tín dụng @break
                                        @case('online') 🌐 Thanh toán online @break
                                        @default {{ $order->payment_method }}
                                    @endswitch
                                </p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">🚚 Ngày giao hàng:</p>
                                <p class="font-medium">{{ $order->delivery_date ? $order->delivery_date->format('d/m/Y') : 'Chưa xác định' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">🔄 Cập nhật cuối:</p>
                                <p class="font-medium">{{ $order->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">💰 Tổng tiền:</p>
                                <p class="text-2xl font-bold text-blue-600">{{ number_format($order->total_amount) }}₫</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                                <p class="text-sm text-gray-600">✉️ Email:</p>
                                <p class="font-medium">{{ $order->customer_email ?: 'Không có' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">📞 Số điện thoại:</p>
                                <p class="font-medium">{{ $order->customer_phone }}</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">📍 Địa chỉ giao hàng:</p>
                                <p class="font-medium">{{ $order->shipping_address ?? $order->customer_address }}</p>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh mục</th>
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
                                    {{ $item->product && $item->product->category ? $item->product->category->name : 'N/A' }}
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
                    <div class="flex justify-end space-y-2">
                        <div class="text-right space-y-2">
                            <div class="flex justify-between min-w-[300px]">
                                <span class="text-sm text-gray-600">💳 Tạm tính:</span>
                                <span class="text-sm font-medium">{{ number_format($subtotal) }}₫</span>
                            </div>
                            @if($order->discount > 0)
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">🎫 Giảm giá ({{ $order->discount }}%):</span>
                                    <span class="text-sm font-medium text-green-600">-{{ number_format($subtotal * $order->discount / 100) }}₫</span>
                                </div>
                            @endif
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

        <!-- Cột bên -->
        <div class="space-y-6">
            <!-- Cập nhật trạng thái -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">⚡ Hành động nhanh</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="mb-4">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cập nhật trạng thái:</label>
                            <select name="status" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>⏳ Chờ xử lý</option>
                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>✅ Đã xác nhận</option>
                                <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>👨‍🍳 Đang chuẩn bị</option>
                                <option value="delivering" {{ $order->status == 'delivering' ? 'selected' : '' }}>🚚 Đang giao</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>🎉 Hoàn thành</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>❌ Đã hủy</option>
                            </select>
                        </div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                            🔄 Cập nhật trạng thái
                        </button>
                    </form>

                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('admin.orders.edit', $order) }}"
                           class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded text-center text-sm">
                            ✏️ Sửa
                        </a>
                        <button onclick="confirmDelete({{ $order->id }})"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                            🗑️ Xóa
                        </button>
                    </div>
                </div>
            </div>

            <!-- Thống kê -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">📊 Thống kê</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <div class="flex items-center">
                            <div class="text-blue-600 text-2xl mr-3">🛒</div>
                            <div>
                                <p class="text-sm text-gray-600">Tổng sản phẩm</p>
                                <p class="text-xl font-bold text-blue-600">{{ $order->orderItems->sum('quantity') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                        <div class="flex items-center">
                            <div class="text-green-600 text-2xl mr-3">💰</div>
                            <div>
                                <p class="text-sm text-gray-600">Giá trị đơn hàng</p>
                                <p class="text-xl font-bold text-green-600">{{ number_format($order->total_amount) }}₫</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                        <div class="flex items-center">
                            <div class="text-yellow-600 text-2xl mr-3">⏰</div>
                            <div>
                                <p class="text-sm text-gray-600">Thời gian tạo</p>
                                <p class="text-sm font-medium text-yellow-600">{{ $order->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lịch sử -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">📋 Lịch sử</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 text-sm">➕</span>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">Đơn hàng được tạo</p>
                                <p class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        @if($order->updated_at != $order->created_at)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <span class="text-yellow-600 text-sm">✏️</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Đơn hàng được cập nhật</p>
                                    <p class="text-xs text-gray-500">{{ $order->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(orderId) {
            if (confirm('❌ Bạn có chắc chắn muốn xóa đơn hàng này?\n\n⚠️ Hành động này không thể hoàn tác!')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/orders/${orderId}`;

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

        // Auto dismiss alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

        // Print styles
        const printStyles = `
            @media print {
                .bg-gray-500, .bg-yellow-500, .bg-blue-500, .bg-red-500 {
                    display: none !important;
                }
                .shadow, .rounded-lg {
                    box-shadow: none !important;
                    border-radius: 0 !important;
                }
                .border-b {
                    border-bottom: 2px solid #000 !important;
                }
            }
        `;

        const styleSheet = document.createElement('style');
        styleSheet.textContent = printStyles;
        document.head.appendChild(styleSheet);
    </script>
</x-admin-layout>
