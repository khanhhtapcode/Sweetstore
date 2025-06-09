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
                                        <!-- COD -->
                                        <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition-colors">
                                            <input type="radio" name="payment_method" value="cod" class="mr-3"
                                                   {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }}>
                                            <div class="flex items-center flex-1">
                                                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                                    💵
                                                </div>
                                                <div>
                                                    <div class="font-medium">Thanh toán khi nhận hàng (COD)</div>
                                                    <div class="text-sm text-gray-600">Thanh toán bằng tiền mặt khi nhận hàng</div>
                                                </div>
                                            </div>
                                        </label>

                                        <!-- Bank Transfer -->
                                        <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition-colors">
                                            <input type="radio" name="payment_method" value="bank_transfer" class="mr-3"
                                                   {{ old('payment_method') === 'bank_transfer' ? 'checked' : '' }}>
                                            <div class="flex items-center flex-1">
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                    🏦
                                                </div>
                                                <div>
                                                    <div class="font-medium">Chuyển khoản ngân hàng</div>
                                                    <div class="text-sm text-gray-600">Chuyển khoản trước khi giao hàng</div>
                                                </div>
                                            </div>
                                        </label>

                                        <!-- VNPay -->
                                        <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition-colors">
                                            <input type="radio" name="payment_method" value="vnpay" class="mr-3"
                                                   {{ old('payment_method') === 'vnpay' ? 'checked' : '' }} onclick="submitVnpayForm()">
                                            <div class="flex items-center flex-1">
                                                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3 border border-gray-200">
                                                    <img src="https://vnpay.vn/assets/images/logo-vnpay.png"
                                                         alt="VNPay Logo" class="w-8 h-8">
                                                </div>
                                                <div>
                                                    <div class="font-medium">Thanh toán qua VNPay</div>
                                                    <div class="text-sm text-gray-600">Thanh toán online qua cổng VNPay</div>
                                                </div>
                                            </div>
                                        </label>

                                        <!-- Credit Card -->
                                        <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition-colors">
                                            <input type="radio" name="payment_method" value="credit_card" class="mr-3"
                                                   {{ old('payment_method') === 'credit_card' ? 'checked' : '' }}>
                                            <div class="flex items-center flex-1">
                                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                                    💳
                                                </div>
                                                <div>
                                                    <div class="font-medium">Thẻ tín dụng/ghi nợ</div>
                                                    <div class="text-sm text-gray-600">Thanh toán online bằng thẻ</div>
                                                </div>
                                            </div>
                                        </label>

                                        <!-- MoMo -->
                                        <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50 transition-colors relative">
                                            <input type="radio" name="payment_method" value="momo" class="mr-3"
                                                   {{ old('payment_method') === 'momo' ? 'checked' : '' }} onclick="showMomoModal()">
                                            <div class="flex items-center flex-1">
                                                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3 border border-gray-200">
                                                    <img src="https://homepage.momocdn.net/fileuploads/svg/momo-file-240411162904.svg"
                                                         alt="MoMo Logo" class="w-8 h-8">
                                                </div>
                                                <div>
                                                    <div class="font-medium flex items-center">
                                                        Ví MoMo
                                                        <span class="ml-2 px-2 py-0.5 bg-pink-100 text-pink-800 text-xs rounded-full">Phổ biến</span>
                                                    </div>
                                                    <div class="text-sm text-gray-600">Thanh toán nhanh chóng với ví MoMo</div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    @error('payment_method')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit" form="checkout-form"
                                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 font-medium text-lg">
                                    Đặt hàng
                                </button>
                            </form>

                            <!-- Form ẩn cho VNPay -->
                            <form id="vnpay-form" action="{{ route('vnpay.payment') }}" method="POST" style="display: none;">
                                @csrf
                                <input type="hidden" name="redirect" value="1">
                                <input type="hidden" name="customer_name" id="vnpay_customer_name" value="">
                                <input type="hidden" name="customer_email" id="vnpay_customer_email" value="">
                                <input type="hidden" name="customer_phone" id="vnpay_customer_phone" value="">
                                <input type="hidden" name="customer_address" id="vnpay_customer_address" value="">
                                <input type="hidden" name="notes" id="vnpay_notes" value="">
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

    <!-- MoMo Payment Modal -->
    <div id="momoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold flex items-center">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center mr-3 border border-gray-200">
                                <img src="https://homepage.momocdn.net/fileuploads/svg/momo-file-240411162904.svg"
                                     alt="MoMo Logo" class="w-8 h-8">
                            </div>
                            Thanh toán MoMo
                        </h3>
                        <button onclick="closeMomoModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="text-center">
                        <div class="mb-4">
                            <div class="mx-auto w-32 h-32 bg-white border-2 border-gray-300 rounded-lg flex items-center justify-center mb-4">
                                <!-- QR Code placeholder -->
                                <div class="w-24 h-24 bg-gray-200 rounded flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 16h4.01M4 12h4.01M8 16h4.01"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">Quét mã QR bằng ứng dụng MoMo</p>
                            <p class="text-lg font-semibold text-pink-600" id="momoAmount">{{ number_format($total ?? 0, 0, ',', '.') }}đ</p>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center justify-center space-x-2 text-sm text-gray-600">
                                <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                <span>Đang chờ thanh toán...</span>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-gray-600 mb-2">Hướng dẫn thanh toán:</p>
                                <ol class="text-xs text-gray-600 space-y-1 text-left">
                                    <li>1. Mở ứng dụng MoMo trên điện thoại</li>
                                    <li>2. Chọn "Quét QR" và quét mã trên</li>
                                    <li>3. Xác nhận thanh toán trong ứng dụng</li>
                                    <li>4. Đợi xác nhận từ hệ thống</li>
                                </ol>
                            </div>
                        </div>

                        <div class="mt-6 flex space-x-3">
                            <button onclick="simulateMomoPayment()"
                                    class="flex-1 bg-pink-500 text-white py-2 px-4 rounded-md hover:bg-pink-600 text-sm">
                                Mô phỏng thanh toán thành công
                            </button>
                            <button onclick="closeMomoModal()"
                                    class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-400 text-sm">
                                Hủy
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value;

            if (!paymentMethod) {
                e.preventDefault();
                alert('Vui lòng chọn phương thức thanh toán.');
                return;
            }

            if (paymentMethod === 'momo') {
                e.preventDefault();
                showMomoModal();
                return;
            } else if (paymentMethod === 'vnpay') {
                e.preventDefault();
                // Cập nhật các trường hidden trong form vnpay-form
                document.getElementById('vnpay_customer_name').value = document.getElementById('customer_name').value;
                document.getElementById('vnpay_customer_email').value = document.getElementById('customer_email').value;
                document.getElementById('vnpay_customer_phone').value = document.getElementById('customer_phone').value;
                document.getElementById('vnpay_customer_address').value = document.getElementById('customer_address').value;
                document.getElementById('vnpay_notes').value = document.getElementById('notes').value;
                document.getElementById('vnpay-form').submit();
                return;
            }

            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Đang xử lý...';
        });

        function showMomoModal() {
            document.getElementById('momoModal').classList.remove('hidden');

            // Simulate QR code generation
            setTimeout(() => {
                console.log('QR Code generated for MoMo payment');
            }, 500);
        }

        function closeMomoModal() {
            document.getElementById('momoModal').classList.add('hidden');
        }

        function simulateMomoPayment() {
            // Show loading state
            const modal = document.getElementById('momoModal');
            const content = modal.querySelector('.bg-white');

            content.innerHTML = `
                <div class="p-6 text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-green-600 mb-2">Thanh toán thành công!</h3>
                    <p class="text-gray-600 mb-4">Giao dịch MoMo đã được xử lý thành công</p>
                    <div class="text-sm text-gray-500">
                        <p>Mã giao dịch: MOMO${Date.now()}</p>
                        <p>Thời gian: ${new Date().toLocaleString('vi-VN')}</p>
                    </div>
                </div>
            `;

            // Close modal and submit form after 2 seconds
            setTimeout(() => {
                closeMomoModal();
                document.getElementById('checkout-form').submit();
            }, 2000);
        }

        // Close modal when clicking outside
        document.getElementById('momoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeMomoModal();
            }
        });
    </script>
</x-app-layout>