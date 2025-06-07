<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Lịch sử đơn hàng
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($orders->isEmpty())
                        <div class="text-center py-12">
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-4">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có đơn hàng nào</h3>
                            <p class="text-gray-600 mb-6">Bạn chưa có đơn hàng nào. Hãy bắt đầu mua sắm ngay!</p>
                            <a href="{{ route('products.index') }}"
                               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Bắt đầu mua sắm
                            </a>
                        </div>
                    @else
                        <div class="space-y-6">
                            @foreach($orders as $order)
                                <div class="border border-gray-200 rounded-lg overflow-hidden">
                                    <!-- Header đơn hàng -->
                                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                            <div class="flex items-center space-x-4">
                                                <div>
                                                    <h3 class="text-lg font-semibold">Đơn hàng #{{ $order->order_number }}</h3>
                                                    <p class="text-sm text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                                </div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($order->status === 'delivered') bg-green-100 text-green-800
                                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                                    @elseif($order->status === 'preparing') bg-blue-100 text-blue-800
                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                                    {{ $order->status_name }}
                                                </span>
                                            </div>
                                            <div class="mt-4 sm:mt-0 flex items-center space-x-4">
                                                <span class="text-lg font-bold text-blue-600">{{ $order->formatted_total }}</span>
                                                <a href="{{ route('orders.detail', $order->id) }}"
                                                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                    Xem chi tiết
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sản phẩm trong đơn hàng -->
                                    <div class="px-6 py-4">
                                        <div class="space-y-4">
                                            @foreach($order->orderItems->take(3) as $item)
                                                <div class="flex items-center space-x-4">
                                                    @if($item->product && $item->product->image_url)
                                                        <img src="{{ asset($item->product->image_url) }}"
                                                             alt="{{ $item->product->name }}"
                                                             class="w-12 h-12 object-cover rounded">
                                                    @else
                                                        <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                                            🧁
                                                        </div>
                                                    @endif
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-gray-900 truncate">
                                                            {{ $item->product ? $item->product->name : 'Sản phẩm đã xóa' }}
                                                        </p>
                                                        <p class="text-sm text-gray-500">
                                                            {{ number_format($item->price, 0, ',', '.') }}đ x {{ $item->quantity }}
                                                        </p>
                                                    </div>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ number_format($item->quantity * $item->price, 0, ',', '.') }}đ
                                                    </p>
                                                </div>
                                            @endforeach

                                            @if($order->orderItems->count() > 3)
                                                <div class="text-center">
                                                    <p class="text-sm text-gray-500">
                                                        Và {{ $order->orderItems->count() - 3 }} sản phẩm khác...
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Footer với thao tác -->
                                    <div class="bg-gray-50 px-6 py-3 flex justify-between items-center">
                                        <div class="text-sm text-gray-600">
                                            Tổng {{ $order->orderItems->count() }} sản phẩm
                                        </div>
                                        <div class="flex space-x-3">
                                            @if($order->status === 'pending')
                                                <button onclick="cancelOrder({{ $order->id }})"
                                                        class="text-sm text-red-600 hover:text-red-800 font-medium">
                                                    Hủy đơn hàng
                                                </button>
                                            @endif
                                            <a href="{{ route('orders.detail', $order->id) }}"
                                               class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                                Xem chi tiết →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($orders->hasPages())
                            <div class="mt-8">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function cancelOrder(orderId) {
            if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
                // Tạo form để gửi request hủy đơn hàng
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/orders/${orderId}/cancel`;

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

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</x-app-layout>
