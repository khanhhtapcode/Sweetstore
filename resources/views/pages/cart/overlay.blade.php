<?php
    // Không cần khởi tạo $cart từ session nữa vì dữ liệu lấy từ $cartItems
    $totalPrice = $totalPrice ?? 0; // Đảm bảo $totalPrice có giá trị
?>

<button id="closeCart" onclick="closeCartOverlay()">×</button>
<div class="cart-container">
    @if($cartItems->isEmpty())
        <div class="cart-empty">
            <p>Giỏ hàng của bạn đang trống!</p>
            <a href="{{ route('products.index') }}">Tiếp tục mua sắm</a>
        </div>
    @else
        <div class="cart-items">
            @foreach($cartItems as $item)
                <div class="cart-item border-b py-4">
                    <img src="{{ $item->image_url }}" alt="{{ $item->product_name }}" class="w-16 h-16 rounded">
                    <div class="flex-1">
                        <h4 class="font-semibold">{{ $item->product_name }}</h4>
                        <p class="text-gray-600">{{ number_format($item->price, 0, ',', '.') }} VNĐ</p>
                        <div class="flex items-center mt-2">
                            <form action="{{ route('cart.update') }}" method="POST" class="cart-form flex items-center">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                <input type="hidden" name="action" value="decrease">
                                <button type="submit" class="cart_quantity_down px-2 py-1 bg-gray-200 rounded">-</button>
                            </form>
                            <span class="mx-2">{{ $item->quantity }}</span>
                            <form action="{{ route('cart.update') }}" method="POST" class="cart-form flex items-center">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                <input type="hidden" name="action" value="increase">
                                <button type="submit" class="cart_quantity_up px-2 py-1 bg-gray-200 rounded">+</button>
                            </form>
                            <a href="{{ route('cart.delete', $item->product_id) }}" class="delete-btn ml-4 text-red-600" data-product-id="{{ $item->product_id }}">Xóa</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="cart-total">
            Tổng tiền: {{ number_format($totalPrice, 0, ',', '.') }} VNĐ
        </div>
        <div class="cart-actions mt-4">
            <a href="{{ route('cart.show_cart') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Xem giỏ hàng</a>
            <a href="#" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Thanh toán</a>
        </div>
    @endif
</div>