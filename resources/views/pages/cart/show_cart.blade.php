<style>
    /* Container giỏ hàng */
    .cart-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #2d3748;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAACklEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg=='); /* Placeholder for mountain background */
        background-size: cover;
        background-position: bottom;
    }

    /* Thông báo giỏ hàng trống */
    .cart-empty {
        text-align: center;
        padding: 50px 20px;
        background: linear-gradient(135deg, #fef3f3, #fff5f7);
        border: 1px solid #fed7d7;
        border-radius: 10px;
    }

    .cart-empty p {
        font-size: 1.5rem;
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 24px;
    }

    .cart-empty a {
        display: inline-block;
        padding: 12px 24px;
        background: linear-gradient(to right, #ec4899, #f472b6);
        color: #fff;
        text-decoration: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 500;
        transition: transform 0.2s, background 0.3s;
    }

    .cart-empty a:hover {
        background: linear-gradient(to right, #db2777, #ec4899);
        transform: translateY(-2px);
    }

    /* Tiêu đề giỏ hàng */
    .cart-container h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 24px;
        text-transform: uppercase;
        text-align: center;
        background-color: #a3e4db;
        padding: 10px;
        border-radius: 8px;
    }

    /* Danh sách sản phẩm */
    .cart-items {
        max-height: 60vh;
        overflow-y: auto;
    }

    .cart-item {
        display: flex;
        align-items: center;
        gap: 16px;
        border-bottom: 1px solid #e5e7eb;
        padding: 16px 0;
        transition: background-color 0.2s ease;
        background-color: rgba(255, 255, 255, 0.8);
    }

    .cart-item:hover {
        background-color: #f9fafb;
    }

    /* Ảnh sản phẩm */
    .cart-item .product-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }

    .cart-item .product-placeholder {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #fed7e2, #fef3f3);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        border: 1px solid #e5e7eb;
    }

    .cart-item .product-info {
        flex: 1;
    }

    .cart-item .product-name {
        font-size: 1.1rem;
        font-weight: 500;
        color: #1f2937;
        text-transform: uppercase;
    }

    .cart-item .product-price-quantity {
        font-size: 0.9rem;
        color: #4a5568;
    }

    /* Nút tăng/giảm số lượng */
    .cart_quantity {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .cart_quantity_up,
    .cart_quantity_down {
        background-color: #f3f4f6;
        color: #1f2937;
        border: none;
        padding: 2px 8px;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s;
    }

    .cart_quantity_up:hover,
    .cart_quantity_down:hover {
        background-color: #e5e7eb;
        transform: translateY(-1px);
    }

    .cart_quantity_input {
        width: 30px;
        text-align: center;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        padding: 2px;
        font-size: 0.9rem;
        background-color: #fff;
        color: #1f2937;
    }

    /* Nút xóa */
    .cart-item .delete-btn {
        color: #dc2626;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: color 0.3s ease;
        font-size: 1rem;
        display: none; /* Hide delete button as per second image */
    }

    .cart-item .delete-btn:hover {
        color: #b91c1c;
        text-decoration: underline;
    }

    /* Tổng tiền */
    .cart-total {
        margin-top: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 1.5rem;
        font-weight: 700;
        color: #111827;
        padding: 16px 20px;
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 8px;
    }

    .cart-total span:last-child {
        color: #dc2626;
    }

    /* Nút hành động */
    .cart-actions {
        display: flex;
        gap: 16px;
        margin-top: 24px;
    }

    .cart-actions a {
        flex: 1;
        text-align: center;
        padding: 12px;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 500;
        text-decoration: none;
        transition: background-color 0.3s, transform 0.2s;
        background-color: #8B4513;
        color: #fff;
    }

    .cart-actions .view-cart {
        background-color: #fff;
        border: 1px solid #d1d5db;
        color: #2d3748;
        display: none; /* Hide view cart button as per second image */
    }

    .cart-actions .view-cart:hover {
        background-color: #f3f4f6;
        transform: translateY(-2px);
    }

    .cart-actions .checkout {
        background-color: #8B4513;
        color: #fff;
    }

    .cart-actions .checkout:hover {
        background-color: #723A0F;
        transform: translateY(-2px);
    }

    /* Responsive: trên màn hình nhỏ */
    @media (max-width: 768px) {
        .cart-container {
            padding: 15px;
            margin: 20px;
        }

        .cart-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 0;
        }

        .cart-item .product-image,
        .cart-item .product-placeholder {
            width: 50px;
            height: 50px;
        }

        .cart-item .product-info {
            width: 100%;
        }

        .cart_quantity {
            width: 100%;
            justify-content: flex-start;
        }

        .cart-total {
            font-size: 1.3rem;
            flex-direction: column;
            gap: 8px;
            text-align: center;
        }

        .cart-actions {
            flex-direction: column;
            gap: 12px;
        }

        .cart-actions a {
            width: 100%;
            padding: 10px;
        }
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                Giỏ hàng
            </h2>
        </div>
    </x-slot>

    @if(empty($cart) || count($cart) == 0)
        <div class="cart-container cart-empty">
            <p>Giỏ hàng đang trống</p>
            <a href="{{ route('home') }}">Tiếp tục mua sắm</a>
        </div>
    @else
        <div class="cart-container">
            <h2>GIỎ HÀNG</h2>
            <div class="cart-items">
                @foreach($cart as $item)
                    @php
                        $subtotal = $item['quantity'] * $item['product_price'];
                        $imageUrl = $item['product_image'] ? asset($item['product_image']) : null;
                    @endphp
                    <div class="cart-item">
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $item['product_name'] }}" class="product-image">
                        @else
                            <div class="product-placeholder">🧁</div>
                        @endif
                        <div class="product-info">
                            <p class="product-name">{{ $item['product_name'] }}</p>
                            <p class="product-price-quantity">{{ $item['quantity'] }} x {{ number_format($item['product_price'], 0, ',', '.') }}đ</p>
                        </div>
                        <div class="cart_quantity">
                            <form action="{{ route('cart.update') }}" method="POST" style="display:inline;" class="cart-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                <input type="hidden" name="action" value="increase">
                                <button type="submit" class="cart_quantity_up" data-loading-text="...">+</button>
                            </form>
                            <input class="cart_quantity_input" type="text" name="quantity" value="{{ $item['quantity'] }}" autocomplete="off" readonly>
                            <form action="{{ route('cart.update') }}" method="POST" style="display:inline;" class="cart-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                <input type="hidden" name="action" value="decrease">
                                <button type="submit" class="cart_quantity_down" data-loading-text="...">-</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            @php
                $totalPrice = array_sum(array_map(fn($item) => $item['quantity'] * $item['product_price'], $cart));
            @endphp
            <div class="cart-total">
                <span>TỔNG TIỀN</span>
                <span>{{ number_format($totalPrice, 0, ',', '.') }}đ</span>
            </div>

            <div class="cart-actions">
                <a href="#" class="checkout">THANH TOÁN</a>
            </div>
        </div>
    @endif
</x-app-layout>