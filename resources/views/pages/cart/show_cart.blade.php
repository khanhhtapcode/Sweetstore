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
                <a href="{{ route('checkout.show') }}" class="checkout-btn">Thanh toán</a>
            </div>
        </div>
    </form>
    <!-- Footer -->
<footer class="bg-gray-900 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <img src="{{ asset('images/sweet-delights-logo.svg') }}" alt="Sweet Delights Logo" class="h-20 w-auto mb-4">
                <p class="text-gray-400 mb-4">Bánh ngọt tươi ngon được làm với tình yêu và sự tận tâm từ năm 2014.</p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition duration-200">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-200">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition duration-200">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.097.118.112.165.085.289-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z" />
                        </svg>
                    </a>
                </div>
            </div>

            <div>
                <h4 class="font-semibold mb-4">Liên Kết Nhanh</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#home" class="hover:text-white transition duration-200">Trang Chủ</a></li>
                    <li><a href="{{ route('products.index') }}" class="hover:text-white transition duration-200">Sản Phẩm</a></li>
                    <li><a href="#categories" class="hover:text-white transition duration-200">Danh Mục</a></li>
                    <li><a href="#about" class="hover:text-white transition duration-200">Về Chúng Tôi</a></li>
                    <li><a href="#contact" class="hover:text-white transition duration-200">Liên Hệ</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold mb-4">Hỗ Trợ Khách Hàng</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white transition duration-200">Chính Sách Đổi Trả</a></li>
                    <li><a href="#" class="hover:text-white transition duration-200">Hướng Dẫn Đặt Hàng</a></li>
                    <li><a href="#" class="hover:text-white transition duration-200">Chính Sách Giao Hàng</a></li>
                    <li><a href="#" class="hover:text-white transition duration-200">Câu Hỏi Thường Gặp</a></li>
                    <li><a href="#" class="hover:text-white transition duration-200">Điều Khoản Sử Dụng</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold mb-4">Thông Tin Liên Hệ</h4>
                <div class="text-gray-400 space-y-3">
                    <p class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Học viện Ngân Hàng<br>TP. Hà Nội
                    </p>
                    <p class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        0123 456 789
                    </p>
                    <p class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        hkkhanhpro@gmail.com
                    </p>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
            <p>© 2024 Sweet Delights. Tất cả quyền được bảo lưu. Thiết kế bởi Family Guys Team.</p>
        </div>
    </div>
</footer>
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