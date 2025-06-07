<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Đặt hàng thành công
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <!-- Thông báo thành công -->
                    <div class="text-center mb-8">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">Đặt hàng thành công!</h1>
                        <p class="text-gray-600">Cảm ơn bạn đã đặt hàng. Chúng tôi sẽ liên hệ với bạn sớm nhất có thể.</p>
                    </div>

                    <!-- Thông tin đơn hàng -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h2 class="text-lg font-semibold mb-4">Thông tin đơn hàng</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Mã đơn hàng:</p>
                                <p class="font-semibold text-lg">#{{ $order->order_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Ngày đặt:</p>
                                <p class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tổng tiền:</p>
                                <p class="font-semibold text-lg text-blue-600">{{ $order->formatted_total }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Trạng thái:</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    {{ $order->status_name }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin giao hàng -->
                    <div class="border-b border-gray-200 pb-6 mb-6">
                        <h3 class="text-lg font-semibold mb-4">Thông tin giao hàng</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Người nhận:</p>
                                <p class="font-medium">{{ $order->customer_name }}</p>

                                <p class="text-sm text-gray-600 mb-1 mt-3">Số điện thoại:</p>
                                <p class="font-medium">{{ $order->customer_phone }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Email:</p>
                                <p class="font-medium">{{ $order->customer_email }}</p>

                                <p class="text-sm text-gray-600 mb-1 mt-3">Địa chỉ giao hàng:</p>
                                <p class="font-medium">{{ $order->customer_address }}</p>
                            </div>
                        </div>
                        @if($order->notes)
                            <div class="mt-4">
                                <p class="text-sm text-gray-600 mb-1">Ghi chú:</p>
                                <p class="font-medium">{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Chi tiết sản phẩm -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Chi tiết sản phẩm</h3>
                        <div class="space-y-4">
                            @php $subtotal = 0; @endphp
                            @foreach($order->orderItems as $item)
                                @php
                                    $itemTotal = $item->quantity * $item->price;
                                    $subtotal += $itemTotal;
                                @endphp
                                <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                                    @if($item->product && $item->product->image_url)
                                        <img src="{{ asset($item->product->image_url) }}"
                                             alt="{{ $item->product->name }}"
                                             class="w-16 h-16 object-cover rounded">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                            🧁
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h4 class="font-medium">{{ $item->product ? $item->product->name : 'Sản phẩm đã xóa' }}</h4>
                                        <p class="text-sm text-gray-600">
                                            {{ number_format($item->price, 0, ',', '.') }}đ x {{ $item->quantity }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold">{{ number_format($itemTotal, 0, ',', '.') }}đ</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Tổng tiền -->
                        <div class="mt-6 bg-gray-50 rounded-lg p-4">
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span>Tạm tính:</span>
                                    <span>{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                                </div>
                                @php $shipping = $order->total_amount - $subtotal; @endphp
                                <div class="flex justify-between text-sm">
                                    <span>Phí vận chuyển:</span>
                                    <span>{{ $shipping == 0 ? 'Miễn phí' : number_format($shipping, 0, ',', '.') . 'đ' }}</span>
                                </div>
                                <div class="flex justify-between text-lg font-semibold border-t pt-2">
                                    <span>Tổng cộng:</span>
                                    <span class="text-blue-600">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hành động -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('orders.detail', $order->id) }}"
                           class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Xem chi tiết đơn hàng
                        </a>
                        <a href="{{ route('products.index') }}"
                           class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Tiếp tục mua sắm
                        </a>
                    </div>

                    <!-- Thông tin liên hệ -->
                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-600">
                            Có thắc mắc về đơn hàng? Liên hệ với chúng tôi qua hotline:
                            <a href="tel:1900123456" class="text-blue-600 hover:text-blue-800 font-medium">1900 123 456</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
