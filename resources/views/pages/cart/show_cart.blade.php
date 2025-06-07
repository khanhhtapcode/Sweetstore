<style>
    /* Giữ nguyên CSS của bạn */
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

    <!-- Đảm bảo CSRF token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if ($cartItems->isEmpty())
    <div class="cart-container cart-empty">
        <p>Giỏ hàng của bạn đang trống</p>
        <a href="{{ route('home') }}">Tiếp tục mua sắm</a>
    </div>
    @else
    <form action="{{ route('cart.update_show_cart') }}" method="POST" id="cart-form">
        @csrf
        <div class="cart-container">
            <div class="cart-items">
                @foreach($cartItems as $item)
                @php
                $subtotal = $item->quantity * $item->price;
                $imageUrl = $item->image_url ? asset($item->image_url) : null;
                $productUrl = route('products.show', $item->product_id);
                @endphp
                <div class="cart-item" data-product-id="{{ $item->product_id }}" data-price="{{ $item->price }}">
                    @if($imageUrl)
                    <img src="{{ $imageUrl }}" alt="{{ $item->product_name }}" class="product-image">
                    @else
                    <div class="product-placeholder">🧁</div>
                    @endif
                    <div class="product-info">
                        <a href="{{ $productUrl }}" class="product-name">{{ $item->product_name }}</a>
                        <p class="product-price">Giá: {{ number_format($item->price, 0, ',', '.') }}đ</p>
                        <p class="product-price subtotal">Thành tiền: {{ number_format($subtotal, 0, ',', '.') }}đ</p>
                    </div>
                    <div class="cart_quantity flex items-center gap-4">
                        <button type="button" class="cart_quantity_down" data-product-id="{{ $item->product_id }}">-</button>
                        <input class="cart_quantity_input" type="number" name="quantities[{{ $item->product_id }}]" value="{{ $item->quantity }}" min="0" autocomplete="off">
                        <button type="button" class="cart_quantity_up" data-product-id="{{ $item->product_id }}">+</button>
                        <button type="button" class="delete-btn text-red-600 hover:text-red-800 font-bold" data-product-id="{{ $item->product_id }}">Xóa</button>
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
                <p>Bạn có <strong id="cart-count">{{ $cartItems->sum('quantity') }}</strong> sản phẩm trong giỏ hàng</p>
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
                <button type="submit" class="checkout-btn">Lưu và Thanh toán</button>
            </div>
        </div>
    </form>
    @endif

    <script>
        // --- Show notification ---
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg text-white z-50 transition-all duration-300 transform translate-x-full ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => notification.classList.remove('translate-x-full'), 100);
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // --- Update cart totals ---
        function updateCartTotals() {
            let total = 0;
            let totalQuantity = 0; // Thêm biến để tính tổng số lượng
            document.querySelectorAll('.cart-item').forEach(item => {
                const quantity = parseInt(item.querySelector('.cart_quantity_input').value) || 0;
                const price = parseFloat(item.getAttribute('data-price')) || 0;
                const subtotal = quantity * price;
                item.querySelector('.subtotal').textContent = `Thành tiền: ${subtotal.toLocaleString('vi-VN')}đ`;
                total += subtotal;
                totalQuantity += quantity; // Cộng dồn số lượng
            });

            const shipping = total >= 500000 ? 0 : 30000;
            const finalTotal = total + shipping;

            document.getElementById('cart-count').textContent = totalQuantity; // Cập nhật tổng số lượng
            document.getElementById('temp-total').textContent = total.toLocaleString('vi-VN');
            document.getElementById('shipping-fee').textContent = shipping === 0 ? 'Miễn phí' : shipping.toLocaleString('vi-VN') + 'đ';
            document.getElementById('final-total').textContent = finalTotal.toLocaleString('vi-VN');
        }

        // --- Handle quantity change with AJAX ---
        let isCartActionRunning = false;

        async function updateQuantity(productId, action) {
            if (isCartActionRunning) return;
            isCartActionRunning = true;

            const cartItem = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
            const quantityInput = cartItem.querySelector('.cart_quantity_input');
            let quantity = parseInt(quantityInput.value) || 0;

            if (action === 'increase') {
                quantity++;
            } else if (action === 'decrease' && quantity > 0) {
                quantity--;
            }

            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('product_id', productId);
            formData.append('action', action);

            try {
                const response = await fetch('{{ route("cart.update_show_cart") }}', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Không thể cập nhật giỏ hàng');
                }

                quantityInput.value = quantity;
                if (quantity <= 0) {
                    cartItem.remove();
                    showNotification('Sản phẩm đã được xóa khỏi giỏ hàng 🛒', 'success');
                } else {
                    showNotification(`Đã cập nhật số lượng ${action === 'increase' ? 'tăng' : 'giảm'} 🛒`, 'success');
                }
                updateCartTotals();
            } catch (error) {
                console.error('Error:', error);
                showNotification('Có lỗi xảy ra: ' + error.message, 'error');
                quantityInput.value = parseInt(quantityInput.getAttribute('data-original-value')) || 0; // Rollback
            } finally {
                isCartActionRunning = false;
            }
        }

        // --- Handle delete with AJAX ---
        async function deleteItem(productId) {
            if (isCartActionRunning) return;
            isCartActionRunning = true;

            const cartItem = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('product_id', productId);

            try {
                const response = await fetch('{{ route("cart.delete_from_show_cart", ["productId" => ":id"]) }}'.replace(':id', productId), {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Không thể xóa sản phẩm');
                }

                cartItem.remove();
                showNotification('Sản phẩm đã được xóa khỏi giỏ hàng 🛒', 'success');
                updateCartTotals();
            } catch (error) {
                console.error('Error:', error);
                showNotification('Có lỗi xảy ra: ' + error.message, 'error');
            } finally {
                isCartActionRunning = false;
            }
        }

        // --- Attach event listeners ---
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.cart_quantity_up').forEach(button => {
                button.addEventListener('click', () => {
                    const productId = button.getAttribute('data-product-id');
                    updateQuantity(productId, 'increase');
                });
            });

            document.querySelectorAll('.cart_quantity_down').forEach(button => {
                button.addEventListener('click', () => {
                    const productId = button.getAttribute('data-product-id');
                    updateQuantity(productId, 'decrease');
                });
            });

            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const productId = button.getAttribute('data-product-id');
                    deleteItem(productId);
                });
            });

            // --- Note counter ---
            const note = document.getElementById('order_note');
            const noteCounter = document.getElementById('noteCounter');
            if (note) {
                note.addEventListener('input', () => {
                    noteCounter.textContent = `${note.value.length} / 200 ký tự`;
                });
            }

            // Initial update
            updateCartTotals();
        });
    </script>
</x-app-layout>