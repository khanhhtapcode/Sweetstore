<style>
    /* Container giỏ hàng */
    .cart-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 20px;
        font-family: 'Arial', sans-serif;
        color: #2d3748;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        display: flex;
        gap: 20px;
    }

    /* Cột trái (Danh sách sản phẩm) */
    .cart-items {
        flex: 2;
        padding-right: 20px;
        border-right: 1px solid #e5e7eb;
    }

    .cart-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px 0;
        border-bottom: 1px solid #e5e7eb;
        transition: background-color 0.2s ease;
    }

    .cart-item:hover {
        background-color: #f9fafb;
    }

    .cart-item .product-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }

    .cart-item .product-placeholder {
        width: 80px;
        height: 80px;
        background: #f9fafb;
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
        font-weight: 600;
        color: #1a202c;
        text-decoration: none;
    }

    .cart-item .product-name:hover {
        color: #ec4899;
    }

    .cart-item .product-price {
        font-size: 0.9rem;
        font-weight: 500;
        color: #4a5568;
        margin-top: 4px;
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
        padding: 4px 12px;
        font-size: 1.2rem;
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
        width: 50px;
        text-align: center;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        padding: 4px;
        font-size: 1rem;
        background-color: #fff;
        color: #1f2937;
    }

    /* Ghi chú đơn hàng */
    .cart-note {
        margin-top: 24px;
    }

    .cart-note label {
        font-size: 1rem;
        font-weight: 500;
        color: #2d3748;
        display: block;
        margin-bottom: 8px;
    }

    .cart-note textarea {
        width: 100%;
        min-height: 100px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 12px;
        font-size: 1rem;
        color: #1f2937;
        resize: vertical;
        background-color: #f9fafb;
        transition: border-color 0.3s ease;
    }

    .cart-note textarea:focus {
        border-color: #ec4899;
        outline: none;
    }

    /* Cột phải (Thông tin tổng tiền) */
    .cart-summary {
        flex: 1;
        padding-left: 20px;
    }

    .cart-summary h3 {
        font-size: 1.2rem;
        font-weight: 600;
        color: #1a202c;
        margin-bottom: 16px;
        background-color: #1e40af;
        padding: 8px;
        color: #fff;
        border-radius: 4px;
        text-align: center;
    }

    .cart-summary .total-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: #dc2626;
        margin-top: 16px;
        text-align: center;
    }

    .cart-summary .checkout-btn {
        display: block;
        width: 100%;
        padding: 12px;
        background-color: #f59e0b;
        color: #fff;
        text-align: center;
        text-decoration: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 500;
        margin-top: 24px;
        transition: background-color 0.3s, transform 0.2s;
    }

    .cart-summary .checkout-btn:hover {
        background-color: #d97706;
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .cart-container {
            flex-direction: column;
            padding: 15px;
            margin: 20px;
        }

        .cart-items {
            padding-right: 0;
            border-right: none;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 20px;
        }

        .cart-summary {
            padding-left: 0;
            margin-top: 20px;
        }

        .cart-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .cart-item .product-image,
        .cart-item .product-placeholder {
            width: 60px;
            height: 60px;
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

    @if ($cartItems->isEmpty())
    <div class="cart-container cart-empty">
        <p>Giỏ hàng của bạn đang trống</p>
        <a href="{{ route('home') }}">Tiếp tục mua sắm</a>
    </div>
    @else
    <div class="cart-container">
        <div class="cart-items">
            @foreach($cartItems as $item)
            @php
            $subtotal = $item->quantity * $item->price;
            $imageUrl = $item->image_url ? asset($item->image_url) : null;
            $productUrl = route('products.show', $item->product_id);
            @endphp
            <div class="cart-item" data-product-id="{{ $item->product_id }}">
                @if($imageUrl)
                <img src="{{ $imageUrl }}" alt="{{ $item->product_name }}" class="product-image">
                @else
                <div class="product-placeholder">🧁</div>
                @endif
                <div class="product-info">
                    <a href="{{ $productUrl }}" class="product-name">{{ $item->product_name }}</a>
                    <p class="product-price">Giá: {{ number_format($item->price, 0, ',', '.') }}đ</p>
                    <p class="product-price">Thành tiền: {{ number_format($subtotal, 0, ',', '.') }}đ</p>
                </div>
                <div class="cart_quantity flex items-center gap-4">
                    <form action="{{ route('cart.update') }}" method="POST" class="cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                        <input type="hidden" name="action" value="decrease">
                        <button type="submit" class="cart_quantity_down">-</button>
                    </form>
                    <input class="cart_quantity_input" type="text" name="quantity" value="{{ $item->quantity }}" autocomplete="off" readonly>
                    <form action="{{ route('cart.update') }}" method="POST" class="cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                        <input type="hidden" name="action" value="increase">
                        <button type="submit" class="cart_quantity_up">+</button>
                    </form>
                    <button class="delete-btn text-red-600 hover:text-red-800 font-bold" data-product-id="{{ $item->product_id }}">Xóa</button>
                </div>
            </div>
            @endforeach

            <div class="cart-note">
                <label for="order_note">Ghi chú đơn hàng <small>(VD: Giao sáng mai, lưu ý khi đóng hàng...)</small></label>
                <textarea id="order_note" name="order_note" maxlength="200" placeholder="Nhập thông tin ghi chú của bạn ..." rows="4"></textarea>
                <small id="noteCounter">0 / 200 ký tự</small>
            </div>
        </div>

        <div class="cart-summary">
            <h3>THÔNG TIN ĐƠN HÀNG</h3>
            <p>Bạn có <strong id="cart-count">{{ $cartItems->count() }}</strong> sản phẩm trong giỏ hàng</p>
            @php
            $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
            });
            $shipping = ($total >= 500000) ? 0 : 30000;
            $final = $total + $shipping;
            @endphp
            <ul>
                <li>Tạm tính: <span id="temp-total">{{ number_format($total, 0, ',', '.') }}</span>đ</li>
                <li>Phí giao hàng: <span id="shipping-fee">{{ $shipping == 0 ? 'Miễn phí' : number_format($shipping, 0, ',', '.') . 'đ' }}</span></li>
                <li>VAT: Đã bao gồm</li>
            </ul>
            <div class="total-price">Tổng cộng: <span id="final-total">{{ number_format($final, 0, ',', '.') }}</span>đ</div>

            <form action="#" method="POST">
                @csrf
                <input type="hidden" name="total" value="{{ $final }}">
                <button type="submit" class="checkout-btn">Thanh toán</button>
            </form>
        </div>
    </div>
    @endif

    <!-- JavaScript xử lý AJAX -->
    <script>
        // Đếm ký tự ghi chú
        const note = document.getElementById('order_note');
        const noteCounter = document.getElementById('noteCounter');
        if (note) {
            note.addEventListener('input', () => {
                noteCounter.textContent = `${note.value.length} / 200 ký tự`;
            });
        }

        // Xử lý tăng/giảm số lượng qua AJAX
        document.querySelectorAll('.cart-form').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const button = form.querySelector('button[type="submit"]');
                button.disabled = true;

                const formData = new FormData(form);
                fetch("{{ route('cart.update') }}", {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        button.disabled = false;
                        if (data.success) {
                            const cartItem = form.closest('.cart-item');
                            const quantityInput = cartItem.querySelector('.cart_quantity_input');
                            const productPrice = parseFloat(cartItem.querySelector('.product-price').textContent.replace('Giá:', '').replace(/\D/g, ''));
                            const cartCount = document.getElementById('cart-count');
                            const tempTotal = document.getElementById('temp-total');
                            const shippingFee = document.getElementById('shipping-fee');
                            const finalTotal = document.getElementById('final-total');

                            if (data.cartCount === 0) {
                                window.location.reload();
                            } else {
                                const newQuantity = parseInt(quantityInput.value) + (formData.get('action') === 'increase' ? 1 : -1);
                                if (newQuantity > 0) {
                                    quantityInput.value = newQuantity;
                                    const subtotal = newQuantity * productPrice;
                                    cartItem.querySelectorAll('.product-price')[1].textContent = `Thành tiền: ${subtotal.toLocaleString('vi-VN')}đ`;
                                } else {
                                    cartItem.remove();
                                }

                                const totalPrice = data.totalPrice;
                                const shipping = totalPrice >= 500000 ? 0 : 30000;
                                const finalPrice = totalPrice + shipping;

                                cartCount.textContent = data.cartCount;
                                tempTotal.textContent = totalPrice.toLocaleString('vi-VN');
                                shippingFee.textContent = shipping === 0 ? 'Miễn phí' : shipping.toLocaleString('vi-VN') + 'đ';
                                finalTotal.textContent = finalPrice.toLocaleString('vi-VN');
                            }
                        } else {
                            alert(data.message || 'Có lỗi xảy ra khi cập nhật giỏ hàng.');
                        }
                    })
                    .catch(error => {
                        button.disabled = false;
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra, vui lòng thử lại.');
                    });
            });
        });

        // Xử lý xóa sản phẩm qua AJAX
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                if (!confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) return;

                const productId = this.getAttribute('data-product-id');
                if (!productId) {
                    alert('Không tìm thấy ID sản phẩm!');
                    return;
                }

                button.disabled = true;
                fetch("{{ route('cart.delete', ['productId' => ':id']) }}".replace(':id', productId), {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        button.disabled = false;
                        if (data.success) {
                            const cartItem = this.closest('.cart-item');
                            cartItem.remove();

                            const cartCount = document.getElementById('cart-count');
                            const tempTotal = document.getElementById('temp-total');
                            const shippingFee = document.getElementById('shipping-fee');
                            const finalTotal = document.getElementById('final-total');

                            if (data.cartCount === 0) {
                                window.location.reload();
                            } else {
                                const totalPrice = data.totalPrice;
                                const shipping = totalPrice >= 500000 ? 0 : 30000;
                                const finalPrice = totalPrice + shipping;

                                cartCount.textContent = data.cartCount;
                                tempTotal.textContent = totalPrice.toLocaleString('vi-VN');
                                shippingFee.textContent = shipping === 0 ? 'Miễn phí' : shipping.toLocaleString('vi-VN') + 'đ';
                                finalTotal.textContent = finalPrice.toLocaleString('vi-VN');
                            }
                        } else {
                            alert(data.message || 'Có lỗi xảy ra khi xóa sản phẩm.');
                        }
                    })
                    .catch(error => {
                        button.disabled = false;
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra, vui lòng thử lại.');
                    });
            });
        });
    </script>
</x-app-layout>