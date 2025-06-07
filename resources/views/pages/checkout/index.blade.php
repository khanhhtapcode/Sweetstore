<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('cart.show_cart') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                Thanh toán
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Form thông tin thanh toán -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-6">Thông tin giao hàng</h3>

                            <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                                @csrf

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Họ và tên <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="customer_name" id="customer_name"
                                               value="{{ old('customer_name', Auth::user()->name) }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('customer_name') border-red-500 @enderror"
                                               required>
                                        @error('customer_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">
                                            Email <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" name="customer_email" id="customer_email"
                                               value="{{ old('customer_email', Auth::user()->email) }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('customer_email') border-red-500 @enderror"
                                               required>
                                        @error('customer_email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Số điện thoại <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" name="customer_phone" id="customer_phone"
                                           value="{{ old('customer_phone') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('customer_phone') border-red-500 @enderror"
                                           required>
                                    @error('customer_phone')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-6">
                                    <label for="customer_address" class="block text-sm font-medium text-gray-700 mb-2">
                                        Địa chỉ giao hàng <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="customer_address" id="customer_address" rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('customer_address') border-red-500 @enderror"
                                              required>{{ old('customer_address') }}</textarea>
                                    @error('customer_address')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-6">
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                        Ghi chú đơn hàng
                                    </label>
                                    <textarea name="notes" id="notes" rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                              placeholder="Ghi chú thêm cho đơn hàng (tùy chọn)">{{ old('notes') }}</textarea>
                                </div>

                                <div class="mb-6">
                                    <h4 class="text-lg font-semibold mb-4">Phương thức thanh toán</h4>
                                    <div class="space-y-3">
                                        <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50">
                                            <input type="radio" name="payment_method" value="cod" class="mr-3"
                                                {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }}>
                                            <div>
                                                <div class="font-medium">Thanh toán khi nhận hàng (COD)</div>
                                                <div class="text-sm text-gray-600">Thanh toán bằng tiền mặt khi nhận hàng</div>
                                            </div>
                                        </label>

                                        <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50">
                                            <input type="radio" name="payment_method" value="bank_transfer" class="mr-3"
                                                {{ old('payment_method') === 'bank_transfer' ? 'checked' : '' }}>
                                            <div>
                                                <div class="font-medium">Chuyển khoản ngân hàng</div>
                                                <div class="text-sm text-gray-600">Chuyển khoản trước khi giao hàng</div>
                                            </div>
                                        </label>

                                        <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50">
                                            <input type="radio" name="payment_method" value="credit_card" class="mr-3"
                                                {{ old('payment_method') === 'credit_card' ? 'checked' : '' }}>
                                            <div>
                                                <div class="font-medium">Thẻ tín dụng/ghi nợ</div>
                                                <div class="text-sm text-gray-600">Thanh toán online bằng thẻ</div>
                                            </div>
                                        </label>
                                    </div>
                                    @error('payment_method')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit"
                                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 font-medium text-lg">
                                    Đặt hàng
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Tóm tắt đơn hàng -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sticky top-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Tóm tắt đơn hàng</h3>

                            <div class="space-y-4 mb-6">
                                @foreach($cartItems as $item)
                                    <div class="flex items-center space-x-3">
                                        @if($item->image_url)
                                            <img src="{{ asset($item->image_url) }}" alt="{{ $item->product_name }}"
                                                 class="w-12 h-12 object-cover rounded">
                                        @else
                                            <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                                🧁
                                            </div>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $item->product_name }}</p>
                                            <p class="text-sm text-gray-500">Số lượng: {{ $item->quantity }}</p>
                                        </div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ number_format($item->quantity * $item->price, 0, ',', '.') }}đ
                                        </p>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t pt-4 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span>Tạm tính:</span>
                                    <span>{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span>Phí vận chuyển:</span>
                                    <span>{{ $shipping == 0 ? 'Miễn phí' : number_format($shipping, 0, ',', '.') . 'đ' }}</span>
                                </div>
                                @if($subtotal >= 500000 && $shipping == 0)
                                    <p class="text-xs text-green-600">🎉 Bạn được miễn phí vận chuyển!</p>
                                @endif
                                <div class="flex justify-between text-lg font-semibold border-t pt-2">
                                    <span>Tổng cộng:</span>
                                    <span class="text-blue-600">{{ number_format($total, 0, ',', '.') }}đ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Xử lý form submit
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Đang xử lý...';
        });
    </script>
</x-app-layout>
