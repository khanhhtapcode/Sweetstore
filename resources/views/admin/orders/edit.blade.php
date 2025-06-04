@extends('layouts.admin')

@section('title', 'Chỉnh Sửa Đơn Hàng #' . $order->id)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Chỉnh Sửa Đơn Hàng #{{ $order->id }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Xem Chi Tiết
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>

                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-8">
                                    <!-- Thông tin khách hàng -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Thông Tin Khách Hàng</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="customer_name">Tên Khách Hàng <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('customer_name') is-invalid @enderror"
                                                               id="customer_name" name="customer_name" value="{{ old('customer_name', $order->customer_name) }}" required>
                                                        @error('customer_name')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="customer_email">Email</label>
                                                        <input type="email" class="form-control @error('customer_email') is-invalid @enderror"
                                                               id="customer_email" name="customer_email" value="{{ old('customer_email', $order->customer_email) }}">
                                                        @error('customer_email')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="customer_phone">Số Điện Thoại <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control @error('customer_phone') is-invalid @enderror"
                                                               id="customer_phone" name="customer_phone" value="{{ old('customer_phone', $order->customer_phone) }}" required>
                                                        @error('customer_phone')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="delivery_date">Ngày Giao Hàng</label>
                                                        <input type="date" class="form-control @error('delivery_date') is-invalid @enderror"
                                                               id="delivery_date" name="delivery_date" value="{{ old('delivery_date', $order->delivery_date ? $order->delivery_date->format('Y-m-d') : '') }}">
                                                        @error('delivery_date')
                                                        <span class="invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="shipping_address">Địa Chỉ Giao Hàng <span class="text-danger">*</span></label>
                                                <textarea class="form-control @error('shipping_address') is-invalid @enderror"
                                                          id="shipping_address" name="shipping_address" rows="3" required>{{ old('shipping_address', $order->shipping_address) }}</textarea>
                                                @error('shipping_address')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sản phẩm trong đơn hàng -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Sản Phẩm</h4>
                                            <button type="button" class="btn btn-primary btn-sm float-right" id="add-product">
                                                <i class="fas fa-plus"></i> Thêm Sản Phẩm
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <div id="order-items">
                                                @foreach($order->orderItems as $index => $item)
                                                    <div class="order-item row mb-3">
                                                        <div class="col-md-5">
                                                            <select name="products[{{ $index }}][product_id]" class="form-control product-select" required>
                                                                <option value="">Chọn sản phẩm...</option>
                                                                @foreach($products as $product)
                                                                    <option value="{{ $product->id }}"
                                                                            data-price="{{ $product->price }}"
                                                                        {{ $product->id == $item->product_id ? 'selected' : '' }}>
                                                                        {{ $product->name }} - {{ number_format($product->price) }}đ
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="number" name="products[{{ $index }}][quantity]" class="form-control quantity-input"
                                                                   placeholder="Số lượng" min="1" value="{{ $item->quantity }}" required>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="number" name="products[{{ $index }}][price]" class="form-control price-input"
                                                                   placeholder="Giá" step="0.01" value="{{ $item->price }}" readonly>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn-danger btn-sm remove-product">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <!-- Tóm tắt đơn hàng -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Tóm Tắt Đơn Hàng</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="status">Trạng Thái</label>
                                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                                    <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                                    <option value="confirmed" {{ old('status', $order->status) == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                                    <option value="preparing" {{ old('status', $order->status) == 'preparing' ? 'selected' : '' }}>Đang chuẩn bị</option>
                                                    <option value="delivering" {{ old('status', $order->status) == 'delivering' ? 'selected' : '' }}>Đang giao</option>
                                                    <option value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                                    <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                                </select>
                                                @error('status')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="payment_method">Phương Thức Thanh Toán</label>
                                                <select class="form-control @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method">
                                                    <option value="cash" {{ old('payment_method', $order->payment_method) == 'cash' ? 'selected' : '' }}>Tiền mặt</option>
                                                    <option value="bank_transfer" {{ old('payment_method', $order->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Chuyển khoản</option>
                                                    <option value="credit_card" {{ old('payment_method', $order->payment_method) == 'credit_card' ? 'selected' : '' }}>Thẻ tín dụng</option>
                                                    <option value="online" {{ old('payment_method', $order->payment_method) == 'online' ? 'selected' : '' }}>Thanh toán online</option>
                                                </select>
                                                @error('payment_method')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="discount">Giảm Giá (%)</label>
                                                <input type="number" class="form-control @error('discount') is-invalid @enderror"
                                                       id="discount" name="discount" value="{{ old('discount', $order->discount ?? 0) }}" min="0" max="100" step="0.01">
                                                @error('discount')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <hr>
                                            <div class="order-summary">
                                                <div class="d-flex justify-content-between">
                                                    <span>Tạm tính:</span>
                                                    <span id="subtotal">{{ number_format($order->total_amount) }}đ</span>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span>Giảm giá:</span>
                                                    <span id="discount-amount">0đ</span>
                                                </div>
                                                <div class="d-flex justify-content-between font-weight-bold">
                                                    <span>Tổng cộng:</span>
                                                    <span id="total">{{ number_format($order->total_amount) }}đ</span>
                                                </div>
                                            </div>

                                            <input type="hidden" name="total_amount" id="total_amount" value="{{ $order->total_amount }}">

                                            <div class="form-group mt-3">
                                                <label for="notes">Ghi Chú</label>
                                                <textarea class="form-control @error('notes') is-invalid @enderror"
                                                          id="notes" name="notes" rows="3">{{ old('notes', $order->notes) }}</textarea>
                                                @error('notes')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Thông tin thời gian -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Thông Tin Thời Gian</h4>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>Ngày tạo:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                            <p><strong>Cập nhật lần cuối:</strong> {{ $order->updated_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập Nhật Đơn Hàng
                            </button>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> Xem Chi Tiết
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let itemCount = {{ count($order->orderItems) }};

            // Thêm sản phẩm mới
            document.getElementById('add-product').addEventListener('click', function() {
                const orderItems = document.getElementById('order-items');
                const newItem = document.querySelector('.order-item').cloneNode(true);

                // Cập nhật name attributes
                newItem.querySelectorAll('select, input').forEach(function(element) {
                    if (element.name) {
                        element.name = element.name.replace(/\[\d+\]/, '[' + itemCount + ']');
                        if (element.type !== 'hidden') {
                            element.value = '';
                        }
                    }
                });

                orderItems.appendChild(newItem);
                itemCount++;
                updateTotal();
            });

            // Xóa sản phẩm
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-product') || e.target.parentElement.classList.contains('remove-product')) {
                    if (document.querySelectorAll('.order-item').length > 1) {
                        e.target.closest('.order-item').remove();
                        updateTotal();
                    }
                }
            });

            // Cập nhật giá khi chọn sản phẩm
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('product-select')) {
                    const selectedOption = e.target.options[e.target.selectedIndex];
                    const price = selectedOption.getAttribute('data-price') || 0;
                    const priceInput = e.target.closest('.order-item').querySelector('.price-input');
                    priceInput.value = price;
                    updateTotal();
                }

                if (e.target.classList.contains('quantity-input') || e.target.id === 'discount') {
                    updateTotal();
                }
            });

            // Tính tổng tiền
            function updateTotal() {
                let subtotal = 0;

                document.querySelectorAll('.order-item').forEach(function(item) {
                    const quantity = parseFloat(item.querySelector('.quantity-input').value) || 0;
                    const price = parseFloat(item.querySelector('.price-input').value) || 0;
                    subtotal += quantity * price;
                });

                const discount = parseFloat(document.getElementById('discount').value) || 0;
                const discountAmount = subtotal * (discount / 100);
                const total = subtotal - discountAmount;

                document.getElementById('subtotal').textContent = new Intl.NumberFormat('vi-VN').format(subtotal) + 'đ';
                document.getElementById('discount-amount').textContent = new Intl.NumberFormat('vi-VN').format(discountAmount) + 'đ';
                document.getElementById('total').textContent = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
                document.getElementById('total_amount').value = total;
            }

            // Khởi tạo tính toán ban đầu
            updateTotal();
        });
    </script>
@endsection
