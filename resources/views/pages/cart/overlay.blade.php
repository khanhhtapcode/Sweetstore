<button id="closeCart" onclick="closeCartOverlay()">×</button>
<div class="cart-container">
    @php
        $cart = $cart ?? session('cart', []);
        $totalPrice = array_sum(array_map(function ($item) {
            return $item['quantity'] * $item['product_price'];
        }, $cart));
    @endphp

    @if(empty($cart) || count($cart) == 0)
        <div class="cart-empty">
            <p>Giỏ hàng đang trống</p>
            <a href="{{ route('products.index') }}">Tiếp tục mua sắm</a>
        </div>
    @else
        <h2 class="text-xl font-semibold text-gray-800 mb-6">GIỎ HÀNG</h2>
        <div class="cart-items">
            @foreach($cart as $item)
                @php
                    $subtotal = $item['quantity'] * $item['product_price'];
                    $imageUrl = $item['product_image'] ? asset($item['product_image']) : 'https://via.placeholder.com/50';
                @endphp
                <div class="cart-item flex items-center py-4">
                    @if($imageUrl)
                        <img src="{{ $imageUrl }}"
                             alt="{{ $item['product_name'] }}"
                             class="w-12 h-12 object-cover rounded mr-4">
                    @else
                        <div class="w-12 h-12 bg-gradient-to-br from-pink-100 to-purple-100 flex items-center justify-center rounded mr-4">
                            <span class="text-xl">🧁</span>
                        </div>
                    @endif
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">{{ strtoupper($item['product_name']) }}</p>
                        <p class="text-xs text-gray-500">{{ $item['quantity'] }} x {{ number_format($item['product_price'], 0, ',', '.') }}đ</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <form action="{{ route('cart.update') }}" method="POST" class="cart-form">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                            <input type="hidden" name="action" value="increase">
                            <button type="submit" class="cart_quantity_up bg-gray-200 text-gray-800 px-2 py-1 rounded hover:bg-gray-300 transition duration-200" data-loading-text="...">+</button>
                        </form>
                        <input class="cart_quantity_input w-12 text-center border border-gray-300 rounded" type="text" name="quantity" value="{{ $item['quantity'] }}" autocomplete="off" readonly>
                        <form action="{{ route('cart.update') }}" method="POST" class="cart-form">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                            <input type="hidden" name="action" value="decrease">
                            <button type="submit" class="cart_quantity_down bg-gray-200 text-gray-800 px-2 py-1 rounded hover:bg-gray-300 transition duration-200" data-loading-text="...">-</button>
                        </form>
                        <form action="{{ route('cart.delete', $item['product_id']) }}" method="POST" class="cart-form">
                            @csrf
                            <button type="submit" class="delete-btn text-gray-500 hover:text-red-600 transition duration-200" data-loading-text="...">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="cart-total flex justify-between items-center mt-6">
            <span class="text-lg font-semibold text-gray-800">TỔNG TIỀN</span>
            <span class="text-lg font-bold text-red-600">{{ number_format($totalPrice, 0, ',', '.') }}đ</span>
        </div>
        <div class="cart-actions flex gap-4 mt-6">
            <a href="{{ route('cart.show_cart') }}" class="flex-1 bg-white border border-gray-300 text-gray-800 px-4 py-2 rounded-lg text-center font-medium hover:bg-gray-100 transition duration-200">
                XEM GIỎ HÀNG
            </a>
            <a href="#" class="flex-1 bg-[#8B4513] text-white px-4 py-2 rounded-lg text-center font-medium hover:bg-[#723A0F] transition duration-200">
                THANH TOÁN
            </a>
        </div>
    @endif
</div>