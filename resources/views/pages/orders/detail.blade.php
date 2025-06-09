<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('orders.history') }}" class="text-gray-500 hover:text-gray-700">
                ← Quay lại
            </a>
            <h2 class="font-semibold text-xl text-gray-800">
                Chi tiết đơn hàng #{{ $order->order_number }} 📋
            </h2>
        </div>
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

    <!-- Popup cảm ơn -->
    <div id="thank-you-popup" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg p-6 max-w-md w-full text-center">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">✅ Cảm ơn bạn đã đánh giá!</h3>
            <p class="text-gray-600 mb-6">Đánh giá của bạn giúp chúng tôi cải thiện dịch vụ.</p>
            <div id="driver-average-rating" class="text-yellow-500 text-xl mb-4"></div>
            <a href="{{ route('products.index') }}"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                🛍️ Tiếp tục mua sắm
            </a>
        </div>
    </div>

    <!-- Header đơn hàng -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Đơn hàng #{{ $order->order_number }}</h1>
                <p class="text-gray-600">📅 {{ $order->created_at->format('H:i - d/m/Y') }}</p>
            </div>
            <div class="mt-4 md:mt-0 text-right">
                @php
                $statusConfig = [
                    'pending' => ['emoji' => '⏳', 'class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Chờ xác nhận'],
                    'confirmed' => ['emoji' => '✅', 'class' => 'bg-blue-100 text-blue-800', 'text' => 'Đã xác nhận'],
                    'preparing' => ['emoji' => '👨‍🍳', 'class' => 'bg-purple-100 text-purple-800', 'text' => 'Đang chuẩn bị'],
                    'ready' => ['emoji' => '🚚', 'class' => 'bg-indigo-100 text-indigo-800', 'text' => 'Sẵn sàng giao'],
                    'delivered' => ['emoji' => '✅', 'class' => 'bg-green-100 text-green-800', 'text' => 'Đã giao'],
                    'cancelled' => ['emoji' => '❌', 'class' => 'bg-red-100 text-red-800', 'text' => 'Đã hủy']
                ];
                $config = $statusConfig[$order->status] ?? $statusConfig['pending'];
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['class'] }}">
                    {{ $config['emoji'] }} {{ $config['text'] }}
                </span>
                <p class="text-2xl font-bold text-blue-600 mt-2">{{ number_format($order->total_amount) }}₫</p>
            </div>
        </div>
    </div>

    <!-- Timeline trạng thái -->
    @if($order->status !== 'cancelled')
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">🚀 Trạng thái đơn hàng</h3>
        <div class="flex justify-between items-center">
            @php
            $statuses = [
                'pending' => ['emoji' => '⏳', 'text' => 'Chờ xác nhận'],
                'confirmed' => ['emoji' => '✅', 'text' => 'Đã xác nhận'],
                'preparing' => ['emoji' => '👨‍🍳', 'text' => 'Đang chuẩn bị'],
                'ready' => ['emoji' => '🚚', 'text' => 'Sẵn sàng giao'],
                'delivered' => ['emoji' => '🎉', 'text' => 'Đã giao']
            ];
            $currentIndex = array_search($order->status, array_keys($statuses));
            @endphp

            @foreach($statuses as $status => $info)
            @php
            $index = array_search($status, array_keys($statuses));
            $isActive = $index <= $currentIndex;
            $isCurrent = $status === $order->status;
            @endphp
            <div class="flex flex-col items-center {{ $index > 0 ? 'flex-1' : '' }}">
                @if($index > 0)
                <div class="w-full h-1 {{ $isActive ? 'bg-green-400' : 'bg-gray-300' }} mb-2"></div>
                @endif
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg border-2
                            {{ $isActive ? 'bg-green-100 border-green-400' : 'bg-gray-100 border-gray-300' }}
                            {{ $isCurrent ? 'ring-4 ring-green-200' : '' }}">
                        {{ $info['emoji'] }}
                    </div>
                    <p class="text-xs text-center mt-2 {{ $isActive ? 'text-green-600 font-medium' : 'text-gray-500' }}">
                        {{ $info['text'] }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-center justify-center p-4 bg-red-50 border border-red-200 rounded">
            <span class="text-2xl mr-3">❌</span>
            <p class="text-red-800 font-medium">Đơn hàng đã bị hủy</p>
        </div>
    </div>
    @endif

    <!-- Thông tin giao hàng -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Thông tin giao hàng</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">👤 Người nhận:</p>
                    <p class="font-medium">{{ $order->customer_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">📞 Số điện thoại:</p>
                    <p class="font-medium">{{ $order->customer_phone }}</p>
                </div>
            </div>
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">✉️ Email:</p>
                    <p class="font-medium">{{ $order->customer_email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">📍 Địa chỉ:</p>
                    <p class="font-medium">{{ $order->customer_address }}</p>
                </div>
            </div>
        </div>
        @if($order->notes)
        <div class="mt-4 p-3 bg-gray-50 rounded">
            <p class="text-sm text-gray-600">💬 Ghi chú:</p>
            <p class="font-medium mt-1">{{ $order->notes }}</p>
        </div>
        @endif
        @if($order->driver)
        <div class="mt-4 p-3 bg-gray-50 rounded">
            <p class="text-sm text-gray-600">🚗 Tài xế:</p>
            <p class="font-medium mt-1">{{ $order->driver->name }}</p>
            <p class="font-medium mt-1">📞 Số điện thoại: {{ $order->driver->phone }}</p>
            <p class="text-sm text-gray-600 mt-2">🚚 Loại xe: {{ $order->driver->vehicle_type }}</p>
            <p class="text-sm text-gray-600 mt-2">Biển số: {{ $order->driver->vehicle_number }}</p>

            @if($order->driver->average_rating)
            <p class="text-sm text-gray-600 mt-2">🌟 Điểm trung bình:</p>
            <p class="text-yellow-500 text-xl">
                @for($i = 1; $i <= 5; $i++)
                    <span>{{ $i <= round($order->driver->average_rating) ? '★' : '☆' }}</span>
                @endfor
                ({{ number_format($order->driver->average_rating, 1) }})
            </p>
            @endif
        </div>
        @endif
    </div>

    <!-- Chi tiết sản phẩm -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">🛒 Chi tiết sản phẩm</h3>
        </div>

        <div class="p-6">
            <div class="space-y-4">
                @php $subtotal = 0; @endphp
                @foreach($order->orderItems as $item)
                @php
                $itemTotal = $item->quantity * $item->price;
                $subtotal += $itemTotal;
                @endphp
                <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded hover:bg-gray-50">
                    @if($item->product && $item->product->image_url)
                    <img src="{{ asset($item->product->image_url) }}"
                        alt="{{ $item->product->name }}"
                        class="w-16 h-16 object-cover rounded">
                    @else
                    <div class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center text-2xl">
                        🧁
                    </div>
                    @endif
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">
                            {{ $item->product ? $item->product->name : 'Sản phẩm đã xóa' }}
                        </h4>
                        @if($item->product && $item->product->description)
                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($item->product->description, 80) }}</p>
                        @endif
                        <div class="flex items-center space-x-4 mt-2 text-sm">
                            <span class="text-gray-600">
                                💰 {{ number_format($item->price) }}₫ × {{ $item->quantity }}
                            </span>
                            @if($item->product)
                            <a href="{{ route('products.show', $item->product->id) }}"
                                class="text-blue-600 hover:underline">
                                👁️ Xem sản phẩm →
                            </a>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-lg">{{ number_format($itemTotal) }}₫</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Tổng tiền -->
            <div class="mt-6 bg-gray-50 rounded p-4">
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span>💳 Tạm tính:</span>
                        <span>{{ number_format($subtotal) }}₫</span>
                    </div>
                    @php $shipping = $order->total_amount - $subtotal; @endphp
                    <div class="flex justify-between text-sm">
                        <span>🚚 Phí vận chuyển:</span>
                        <span>{{ $shipping == 0 ? 'Miễn phí' : number_format($shipping) . '₫' }}</span>
                    </div>
                    <div class="border-t pt-2 flex justify-between text-lg font-bold">
                        <span>💰 Tổng cộng:</span>
                        <span class="text-blue-600">{{ number_format($order->total_amount) }}₫</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hành động -->
    <div class="mt-6">
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            @if($order->status === 'pending')
            <button onclick="cancelOrder({{ $order->id }})"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                ❌ Hủy đơn hàng
            </button>
            @endif
            <a href="{{ route('orders.history') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                ← Quay lại lịch sử
            </a>
            <a href="{{ route('products.index') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                🛍️ Tiếp tục mua sắm
            </a>
        </div>
    </div>

    <!-- Đánh giá tài xế -->
    @if($order->status === 'delivered' && $order->driver)
        @if(!$order->driverRating) <!-- Chưa đánh giá -->
            <div class="mt-6 bg-white rounded-lg shadow p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">🚗 Đánh giá tài xế giao hàng</h4>
                <p class="text-sm text-gray-600 mb-4">Hãy cho chúng tôi biết trải nghiệm của bạn với tài xế!</p>
                <form id="driver-rating-form" action="{{ route('driver-ratings.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <input type="hidden" name="driver_id" value="{{ $order->driver->id }}">
                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">

                    <div class="flex items-center space-x-2 mb-4">
                        <label class="text-sm text-gray-700 mr-2">Chọn sao:</label>
                        @for($i = 1; $i <= 5; $i++)
                            <label class="flex flex-col items-center text-xs text-gray-500">
                                <input type="radio" name="rating" value="{{ $i }}" class="sr-only" required>
                                <span class="text-yellow-500 text-2xl cursor-pointer hover:scale-110 transition">{{ $i }}★</span>
                            </label>
                        @endfor
                    </div>

                    <div class="mb-4">
                        <label for="comment" class="block text-sm text-gray-700 mb-1">Nhận xét:</label>
                        <textarea name="comment" id="comment" rows="3"
                                  class="w-full border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-200"
                                  placeholder="Tài xế thân thiện, giao hàng đúng giờ..."></textarea>
                    </div>

                    <button type="submit"
                            class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded">
                        ✅ Gửi đánh giá
                    </button>
                </form>

                <!-- Hiển thị trung bình số sao của tài xế -->
                @if($order->driver->average_rating)
                    <div class="mt-4 p-3 bg-gray-50 rounded">
                        <p class="text-sm text-gray-600">🌟 Điểm trung bình của tài xế:</p>
                        <p class="text-yellow-500 text-xl">
                            @for($i = 1; $i <= 5; $i++)
                                <span>{{ $i <= round($order->driver->average_rating) ? '★' : '☆' }}</span>
                            @endfor
                            ({{ number_format($order->driver->average_rating, 1) }})
                        </p>
                    </div>
                @endif
            </div>
        @else <!-- Đã đánh giá -->
            <div class="mt-6 bg-white rounded-lg shadow p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">📊 Đánh giá tài xế</h4>
                <p class="text-gray-700">
                    Bạn đã đánh giá tài xế <strong>{{ $order->driver->name }}</strong> với:
                </p>
                <p class="text-yellow-500 text-xl mt-1">
                    @for($i = 1; $i <= 5; $i++)
                        <span>{{ $i <= $order->driverRating->rating ? '★' : '☆' }}</span>
                    @endfor
                    <span class="text-gray-800 text-sm ml-2">({{ $order->driverRating->rating }} sao)</span>
                </p>
                @if($order->driverRating->comment)
                    <p class="text-gray-700 italic mt-2">“{{ $order->driverRating->comment }}”</p>
                @endif

                <!-- Hiển thị trung bình số sao của tài xế -->
                @if($order->driver->average_rating)
                    <div class="mt-4 p-3 bg-gray-50 rounded">
                        <p class="text-sm text-gray-600">🌟 Điểm trung bình của tài xế:</p>
                        <p class="text-yellow-500 text-xl">
                            @for($i = 1; $i <= 5; $i++)
                                <span>{{ $i <= round($order->driver->average_rating) ? '★' : '☆' }}</span>
                            @endfor
                            ({{ number_format($order->driver->average_rating, 1) }})
                        </p>
                    </div>
                @endif
            </div>
        @endif
    @endif

    <!-- Hỗ trợ khách hàng -->
    <div class="mt-6 bg-blue-50 rounded-lg border border-blue-200 p-6 text-center">
        <h4 class="text-lg font-semibold text-blue-900 mb-2">🤝 Cần hỗ trợ?</h4>
        <p class="text-blue-700 mb-4">
            Liên hệ với chúng tôi nếu bạn có bất kỳ thắc mắc nào về đơn hàng
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="tel:1900123456"
                class="text-blue-600 hover:underline">
                📞 Hotline: 1900 123 456
            </a>
            <a href="mailto:support@sweetdelights.com"
                class="text-blue-600 hover:underline">
                ✉️ Email: support@sweetdelights.com
            </a>
        </div>
    </div>

    <script>
        // Xử lý hủy đơn hàng
        function cancelOrder(orderId) {
            if (confirm('❌ Bạn có chắc chắn muốn hủy đơn hàng này?')) {
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

        // Xử lý gửi đánh giá qua AJAX
        document.getElementById('driver-rating-form')?.addEventListener('submit', function (e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hiển thị popup cảm ơn
                    const popup = document.getElementById('thank-you-popup');
                    const averageRatingDiv = document.getElementById('driver-average-rating');
                    averageRatingDiv.innerHTML = `🌟 Điểm trung bình của tài xế: ${data.average_rating} / 5`;
                    popup.classList.remove('hidden');

                    // Ẩn form đánh giá
                    form.closest('.bg-white').style.display = 'none';

                    // Tự động ẩn popup sau 5 giây
                    setTimeout(() => {
                        popup.classList.add('hidden');
                    }, 5000);
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Đã có lỗi xảy ra. Vui lòng thử lại.');
            });
        });

        // Auto dismiss alerts
        setTimeout(function () {
            const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</x-app-layout>