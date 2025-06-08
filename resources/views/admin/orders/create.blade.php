<x-admin-layout>
    <x-slot name="header">
        Tạo Đơn Hàng Mới 📋
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Tạo Đơn Hàng Mới</h3>
                <div class="mt-2">
                    <a href="{{ route('admin.orders.index') }}" class="text-gray-600 hover:text-gray-900">
                        ← Quay lại danh sách
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.orders.store') }}" method="POST" class="p-6">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <strong>Có lỗi xảy ra:</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Cột chính - Thông tin khách hàng và sản phẩm -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Thông tin khách hàng -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">👤 Thông Tin Khách Hàng</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">
                                        Tên khách hàng <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="customer_name" id="customer_name"
                                           value="{{ old('customer_name') }}"
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('customer_name') border-red-500 @enderror"
                                           placeholder="Nguyễn Văn A" required>
                                    @error('customer_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-1">
                                        Số điện thoại <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" name="customer_phone" id="customer_phone"
                                           value="{{ old('customer_phone') }}"
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('customer_phone') border-red-500 @enderror"
                                           placeholder="0123456789" required>
                                    @error('customer_phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-1">
                                        Email
                                    </label>
                                    <input type="email" name="customer_email" id="customer_email"
                                           value="{{ old('customer_email') }}"
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('customer_email') border-red-500 @enderror"
                                           placeholder="example@email.com">
                                    @error('customer_email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">
                                        Phương thức thanh toán
                                    </label>
                                    <select name="payment_method" id="payment_method"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>💵 COD (Thanh toán khi nhận hàng)</option>
                                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>🏦 Chuyển khoản ngân hàng</option>
                                        <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>💳 Thẻ tín dụng</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <label for="customer_address" class="block text-sm font-medium text-gray-700 mb-1">
                                    Địa chỉ giao hàng <span class="text-red-500">*</span>
                                </label>
                                <textarea name="customer_address" id="customer_address" rows="3"
                                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('customer_address') border-red-500 @enderror"
                                          placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố"
                                          required>{{ old('customer_address') }}</textarea>
                                @error('customer_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Sản phẩm -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-lg font-semibold text-gray-800">🛒 Sản Phẩm</h4>
                                <button type="button" onclick="addProduct()"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                    ➕ Thêm sản phẩm
                                </button>
                            </div>

                            <div id="products-container">
                                <div class="product-item grid grid-cols-12 gap-2 mb-3 items-end">
                                    <div class="col-span-5">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Sản phẩm</label>
                                        <select name="products[0][product_id]" class="product-select w-full border border-gray-300 rounded-md px-3 py-2 text-sm" required>
                                            <option value="">-- Chọn sản phẩm --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}"
                                                        data-price="{{ $product->price }}"
                                                        data-weight="{{ $product->weight ?? 0.5 }}"
                                                        data-volume="{{ $product->volume ?? 0.01 }}">
                                                    {{ $product->name }} - {{ number_format($product->price) }}₫
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Số lượng</label>
                                        <input type="number" name="products[0][quantity]" class="quantity-input w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                                               min="1" value="1" required>
                                    </div>
                                    <div class="col-span-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Đơn giá</label>
                                        <input type="number" name="products[0][price]" class="price-input w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                                               step="0.01" readonly>
                                    </div>
                                    <div class="col-span-2">
                                        <button type="button" onclick="removeProduct(this)"
                                                class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-2 rounded text-sm">
                                            🗑️ Xóa
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Thông tin vận chuyển -->
                            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                                <h5 class="text-sm font-semibold text-blue-800 mb-2">📦 Thông tin vận chuyển</h5>
                                <div class="grid grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <span class="text-blue-600">Tổng trọng lượng:</span>
                                        <span id="total-weight" class="font-bold">0 kg</span>
                                    </div>
                                    <div>
                                        <span class="text-blue-600">Thể tích:</span>
                                        <span id="total-volume" class="font-bold">0 m³</span>
                                    </div>
                                    <div>
                                        <span class="text-blue-600">Loại xe đề xuất:</span>
                                        <span id="suggested-vehicle" class="font-bold">Chưa xác định</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cột bên - Tóm tắt và tài xế -->
                    <div class="space-y-6">
                        <!-- Tóm tắt đơn hàng -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">💰 Tóm Tắt Đơn Hàng</h4>

                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span>Tạm tính:</span>
                                    <span id="subtotal">0₫</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span>Phí vận chuyển:</span>
                                    <span id="shipping-fee">0₫</span>
                                </div>
                                <div class="border-t pt-3 flex justify-between font-bold">
                                    <span>Tổng cộng:</span>
                                    <span id="total" class="text-blue-600">0₫</span>
                                </div>
                            </div>

                            <input type="hidden" name="total_amount" id="total_amount" value="0">

                            <div class="mt-4">
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                    Trạng thái đơn hàng
                                </label>
                                <select name="status" id="status"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>⏳ Chờ xác nhận</option>
                                    <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>✅ Đã xác nhận</option>
                                    <option value="preparing" {{ old('status') == 'preparing' ? 'selected' : '' }}>👨‍🍳 Đang chuẩn bị</option>
                                    <option value="ready" {{ old('status') == 'ready' ? 'selected' : '' }}>📦 Sẵn sàng giao</option>
                                </select>
                            </div>

                            <div class="mt-4">
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">
                                    Ghi chú
                                </label>
                                <textarea name="notes" id="notes" rows="3"
                                          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                                          placeholder="Ghi chú thêm về đơn hàng...">{{ old('notes') }}</textarea>
                            </div>
                        </div>

                        <!-- Gán tài xế -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">🚚 Gán Tài Xế</h4>

                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" id="auto-assign-driver" class="rounded border-gray-300 text-blue-600 mr-2">
                                    <span class="text-sm">Tự động gán tài xế phù hợp</span>
                                </label>
                            </div>

                            <div id="driver-selection" class="space-y-3">
                                <div>
                                    <label for="driver_id" class="block text-sm font-medium text-gray-700 mb-1">
                                        Chọn tài xế
                                    </label>
                                    <select name="driver_id" id="driver_id"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2">
                                        <option value="">-- Để trống (tự động gán sau) --</option>
                                        @foreach($drivers as $driver)
                                            <option value="{{ $driver->id }}"
                                                    data-vehicle="{{ $driver->vehicle_type }}"
                                                    data-current-orders="{{ $driver->current_orders_count ?? 0 }}"
                                                    data-status="{{ $driver->status }}">
                                                {{ $driver->driver_code }} - {{ $driver->name }}
                                                ({{ $driver->vehicle_type_name }} - {{ $driver->current_orders_count ?? 0 }}/3 đơn)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="driver-info" class="hidden p-3 bg-blue-50 rounded text-sm">
                                    <!-- Driver info will be populated by JavaScript -->
                                </div>

                                <div id="driver-suggestions" class="space-y-2">
                                    <!-- Suggested drivers will be populated by JavaScript -->
                                </div>
                            </div>
                        </div>

                        <!-- Hành động -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="space-y-3">
                                <button type="submit"
                                        class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded">
                                    ✅ Tạo Đơn Hàng
                                </button>
                                <a href="{{ route('admin.orders.index') }}"
                                   class="w-full bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded text-center block">
                                    ❌ Hủy
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        let productIndex = 1;

        document.addEventListener('DOMContentLoaded', function() {
            updateCalculations();
            setupEventListeners();
        });

        function setupEventListeners() {
            // Auto-assign driver checkbox
            document.getElementById('auto-assign-driver').addEventListener('change', function() {
                const driverSelection = document.getElementById('driver-selection');
                if (this.checked) {
                    driverSelection.style.opacity = '0.5';
                    driverSelection.style.pointerEvents = 'none';
                    document.getElementById('driver_id').value = '';
                } else {
                    driverSelection.style.opacity = '1';
                    driverSelection.style.pointerEvents = 'auto';
                }
            });

            // Driver selection change
            document.getElementById('driver_id').addEventListener('change', function() {
                showDriverInfo(this.value);
            });

            // Status change - show driver selection when ready
            document.getElementById('status').addEventListener('change', function() {
                const driverSection = document.querySelector('.bg-gray-50:has(#driver_id)');
                if (this.value === 'ready') {
                    driverSection.classList.remove('hidden');
                    updateDriverSuggestions();
                } else {
                    driverSection.classList.add('hidden');
                }
            });
        }

        function addProduct() {
            const container = document.getElementById('products-container');
            const newProduct = document.createElement('div');
            newProduct.className = 'product-item grid grid-cols-12 gap-2 mb-3 items-end';
            newProduct.innerHTML = `
                <div class="col-span-5">
                    <select name="products[${productIndex}][product_id]" class="product-select w-full border border-gray-300 rounded-md px-3 py-2 text-sm" required>
                        <option value="">-- Chọn sản phẩm --</option>
                        @foreach($products as $product)
            <option value="{{ $product->id }}"
                                    data-price="{{ $product->price }}"
                                    data-weight="{{ $product->weight ?? 0.5 }}"
                                    data-volume="{{ $product->volume ?? 0.01 }}">
                                {{ $product->name }} - {{ number_format($product->price) }}₫
                            </option>
                        @endforeach
            </select>
        </div>
        <div class="col-span-2">
            <input type="number" name="products[${productIndex}][quantity]" class="quantity-input w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                           min="1" value="1" required>
                </div>
                <div class="col-span-3">
                    <input type="number" name="products[${productIndex}][price]" class="price-input w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
                           step="0.01" readonly>
                </div>
                <div class="col-span-2">
                    <button type="button" onclick="removeProduct(this)"
                            class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-2 rounded text-sm">
                        🗑️ Xóa
                    </button>
                </div>
            `;
            container.appendChild(newProduct);
            productIndex++;
            attachProductEvents(newProduct);
        }

        function removeProduct(button) {
            const productItems = document.querySelectorAll('.product-item');
            if (productItems.length > 1) {
                button.closest('.product-item').remove();
                updateCalculations();
            } else {
                alert('Phải có ít nhất một sản phẩm!');
            }
        }

        function attachProductEvents(container = document) {
            container.querySelectorAll('.product-select').forEach(select => {
                select.addEventListener('change', function() {
                    const option = this.options[this.selectedIndex];
                    const priceInput = this.closest('.product-item').querySelector('.price-input');
                    priceInput.value = option.dataset.price || 0;
                    updateCalculations();
                });
            });

            container.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('input', updateCalculations);
            });
        }

        function updateCalculations() {
            let subtotal = 0;
            let totalWeight = 0;
            let totalVolume = 0;

            document.querySelectorAll('.product-item').forEach(item => {
                const select = item.querySelector('.product-select');
                const quantityInput = item.querySelector('.quantity-input');
                const priceInput = item.querySelector('.price-input');

                const quantity = parseFloat(quantityInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const option = select.options[select.selectedIndex];

                subtotal += quantity * price;

                if (option && option.dataset.weight) {
                    totalWeight += quantity * parseFloat(option.dataset.weight);
                }
                if (option && option.dataset.volume) {
                    totalVolume += quantity * parseFloat(option.dataset.volume);
                }
            });

            // Calculate shipping fee based on weight and volume
            const shippingFee = calculateShippingFee(totalWeight, totalVolume);
            const total = subtotal + shippingFee;

            // Update display
            document.getElementById('subtotal').textContent = formatCurrency(subtotal);
            document.getElementById('shipping-fee').textContent = formatCurrency(shippingFee);
            document.getElementById('total').textContent = formatCurrency(total);
            document.getElementById('total_amount').value = total;

            // Update shipping info
            document.getElementById('total-weight').textContent = totalWeight.toFixed(2) + ' kg';
            document.getElementById('total-volume').textContent = totalVolume.toFixed(3) + ' m³';
            document.getElementById('suggested-vehicle').textContent = getSuggestedVehicle(totalWeight, totalVolume);

            // Update driver suggestions if needed
            if (document.getElementById('status').value === 'ready') {
                updateDriverSuggestions();
            }

            // Auto-attach events to new elements
            attachProductEvents();
        }

        function calculateShippingFee(weight, volume) {
            // Simple shipping fee calculation
            if (weight <= 2 && volume <= 0.01) return 25000; // Xe máy
            if (weight <= 20 && volume <= 1) return 50000;   // Xe tải nhỏ
            return 100000; // Xe van
        }

        function getSuggestedVehicle(weight, volume) {
            if (weight <= 5 && volume <= 0.05) return '🏍️ Xe máy';
            if (weight <= 50 && volume <= 2) return '🚚 Xe tải nhỏ';
            return '🚐 Xe van';
        }

        function updateDriverSuggestions() {
            const weight = parseFloat(document.getElementById('total-weight').textContent);
            const volume = parseFloat(document.getElementById('total-volume').textContent);
            const suggestedVehicle = getSuggestedVehicleType(weight, volume);

            const driversSelect = document.getElementById('driver_id');
            const suggestionsDiv = document.getElementById('driver-suggestions');

            // Reset suggestions
            suggestionsDiv.innerHTML = '<h6 class="text-sm font-medium text-gray-700 mb-2">🎯 Tài xế phù hợp:</h6>';

            const suitableDrivers = Array.from(driversSelect.options)
                .filter(option => option.value && option.dataset.vehicle === suggestedVehicle)
                .slice(1, 4); // Top 3 drivers

            if (suitableDrivers.length > 0) {
                suitableDrivers.forEach(option => {
                    const div = document.createElement('div');
                    div.className = 'p-2 bg-green-50 rounded cursor-pointer hover:bg-green-100';
                    div.innerHTML = `
                        <div class="text-sm font-medium">${option.textContent}</div>
                        <div class="text-xs text-gray-600">Phù hợp cho ${getSuggestedVehicle(weight, volume)}</div>
                    `;
                    div.onclick = () => {
                        driversSelect.value = option.value;
                        showDriverInfo(option.value);
                    };
                    suggestionsDiv.appendChild(div);
                });
            } else {
                suggestionsDiv.innerHTML += '<p class="text-sm text-yellow-600">⚠️ Không có tài xế phù hợp với loại xe đề xuất</p>';
            }
        }

        function getSuggestedVehicleType(weight, volume) {
            if (weight <= 5 && volume <= 0.05) return 'motorbike';
            if (weight <= 50 && volume <= 2) return 'small_truck';
            return 'van';
        }

        function showDriverInfo(driverId) {
            const infoDiv = document.getElementById('driver-info');
            if (!driverId) {
                infoDiv.classList.add('hidden');
                return;
            }

            const option = document.querySelector(`option[value="${driverId}"]`);
            if (option) {
                infoDiv.innerHTML = `
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-medium">${option.textContent}</p>
                            <p class="text-xs">Trạng thái: ${getStatusName(option.dataset.status)}</p>
                            <p class="text-xs">Đang giao: ${option.dataset.currentOrders}/3 đơn</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs px-2 py-1 rounded ${getStatusClass(option.dataset.status)}">
                                ${getStatusName(option.dataset.status)}
                            </span>
                        </div>
                    </div>
                `;
                infoDiv.classList.remove('hidden');
            }
        }

        function getStatusName(status) {
            const statuses = {
                'active': '✅ Sẵn sàng',
                'busy': '🚚 Đang bận',
                'inactive': '⏸️ Ngừng hoạt động'
            };
            return statuses[status] || status;
        }

        function getStatusClass(status) {
            const classes = {
                'active': 'bg-green-100 text-green-800',
                'busy': 'bg-yellow-100 text-yellow-800',
                'inactive': 'bg-red-100 text-red-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount) + '₫';
        }

        // Initialize events for existing elements
        attachProductEvents();
    </script>
</x-admin-layout>
