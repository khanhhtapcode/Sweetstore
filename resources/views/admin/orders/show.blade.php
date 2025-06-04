<x-admin-layout>
    <x-slot name="header">
        Chi Tiết Đơn Hàng
    </x-slot>

@section('title', 'Chi Tiết Đơn Hàng #' . $order->id)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Chi Tiết Đơn Hàng #{{ $order->id }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Chỉnh Sửa
                            </a>
                            <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">
                                <i class="fas fa-print"></i> In Đơn Hàng
                            </button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-8">
                                <!-- Thông tin đơn hàng -->
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Thông Tin Đơn Hàng</h4>
                                        <div class="card-tools">
                                        <span class="badge badge-{{ $order->status == 'completed' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'warning') }} badge-lg">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <td><strong>Mã đơn hàng:</strong></td>
                                                        <td>#{{ $order->id }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Ngày tạo:</strong></td>
                                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Trạng thái:</strong></td>
                                                        <td>
                                                        <span class="badge badge-{{ $order->status == 'completed' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'warning') }}">
                                                            @switch($order->status)
                                                                @case('pending') Chờ xử lý @break
                                                                @case('confirmed') Đã xác nhận @break
                                                                @case('preparing') Đang chuẩn bị @break
                                                                @case('delivering') Đang giao @break
                                                                @case('completed') Hoàn thành @break
                                                                @case('cancelled') Đã hủy @break
                                                                @default {{ $order->status }}
                                                            @endswitch
                                                        </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Phương thức thanh toán:</strong></td>
                                                        <td>
                                                            @switch($order->payment_method)
                                                                @case('cash') Tiền mặt @break
                                                                @case('bank_transfer') Chuyển khoản @break
                                                                @case('credit_card') Thẻ tín dụng @break
                                                                @case('online') Thanh toán online @break
                                                                @default {{ $order->payment_method }}
                                                            @endswitch
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <td><strong>Ngày giao hàng:</strong></td>
                                                        <td>{{ $order->delivery_date ? $order->delivery_date->format('d/m/Y') : 'Chưa xác định' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Cập nhật lần cuối:</strong></td>
                                                        <td>{{ $order->updated_at->format('d/m/Y H:i') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Tổng tiền:</strong></td>
                                                        <td><strong class="text-primary">{{ number_format($order->total_amount) }}đ</strong></td>
                                                    </tr>
                                                    @if($order->discount > 0)
                                                        <tr>
                                                            <td><strong>Giảm giá:</strong></td>
                                                            <td><span class="text-success">{{ $order->discount }}%</span></td>
                                                        </tr>
                                                    @endif
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Thông tin khách hàng -->
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Thông Tin Khách Hàng</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <td><strong>Tên khách hàng:</strong></td>
                                                        <td>{{ $order->customer_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Email:</strong></td>
                                                        <td>{{ $order->customer_email ?: 'Không có' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Số điện thoại:</strong></td>
                                                        <td>{{ $order->customer_phone }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <td><strong>Địa chỉ giao hàng:</strong></td>
                                                        <td>{{ $order->shipping_address }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Chi tiết sản phẩm -->
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Chi Tiết Sản Phẩm</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>Sản phẩm</th>
                                                    <th>Danh mục</th>
                                                    <th>Số lượng</th>
                                                    <th>Đơn giá</th>
                                                    <th>Thành tiền</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php $subtotal = 0; @endphp
                                                @foreach($order->orderItems as $item)
                                                    @php $itemTotal = $item->quantity * $item->price; $subtotal += $itemTotal; @endphp
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                @if($item->product && $item->product->image)
                                                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                                                         alt="{{ $item->product->name }}"
                                                                         class="img-thumbnail me-2"
                                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                                @endif
                                                                <div>
                                                                    <strong>{{ $item->product ? $item->product->name : 'Sản phẩm đã xóa' }}</strong>
                                                                    @if($item->product && $item->product->description)
                                                                        <br><small class="text-muted">{{ Str::limit($item->product->description, 50) }}</small>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ $item->product && $item->product->category ? $item->product->category->name : 'N/A' }}</td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td>{{ number_format($item->price) }}đ</td>
                                                        <td><strong>{{ number_format($itemTotal) }}đ</strong></td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th colspan="4" class="text-right">Tạm tính:</th>
                                                    <th>{{ number_format($subtotal) }}đ</th>
                                                </tr>
                                                @if($order->discount > 0)
                                                    <tr>
                                                        <th colspan="4" class="text-right">Giảm giá ({{ $order->discount }}%):</th>
                                                        <th class="text-success">-{{ number_format($subtotal * $order->discount / 100) }}đ</th>
                                                    </tr>
                                                @endif
                                                <tr class="table-primary">
                                                    <th colspan="4" class="text-right">Tổng cộng:</th>
                                                    <th class="text-primary">{{ number_format($order->total_amount) }}đ</th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                @if($order->notes)
                                    <!-- Ghi chú -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Ghi Chú</h4>
                                        </div>
                                        <div class="card-body">
                                            <p>{{ $order->notes }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-4">
                                <!-- Hành động nhanh -->
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Hành Động Nhanh</h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="mb-3">
                                            @csrf
                                            @method('PATCH')
                                            <div class="form-group">
                                                <label for="quick_status">Cập nhật trạng thái:</label>
                                                <select name="status" id="quick_status" class="form-control">
                                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                                    <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                                    <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Đang chuẩn bị</option>
                                                    <option value="delivering" {{ $order->status == 'delivering' ? 'selected' : '' }}>Đang giao</option>
                                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="fas fa-sync-alt"></i> Cập Nhật Trạng Thái
                                            </button>
                                        </form>

                                        <div class="row">
                                            <div class="col-6">
                                                <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-warning btn-block btn-sm">
                                                    <i class="fas fa-edit"></i> Chỉnh Sửa
                                                </a>
                                            </div>
                                            <div class="col-6">
                                                <button type="button" class="btn btn-danger btn-block btn-sm" data-toggle="modal" data-target="#deleteModal">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Thống kê nhanh -->
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Thông Tin Thêm</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-info"><i class="fas fa-shopping-cart"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Tổng sản phẩm</span>
                                                <span class="info-box-number">{{ $order->orderItems->sum('quantity') }}</span>
                                            </div>
                                        </div>

                                        <div class="info-box">
                                            <span class="info-box-icon bg-success"><i class="fas fa-money-bill-wave"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Giá trị đơn hàng</span>
                                                <span class="info-box-number">{{ number_format($order->total_amount) }}đ</span>
                                            </div>
                                        </div>

                                        <div class="info-box">
                                            <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Thời gian tạo</span>
                                                <span class="info-box-number">{{ $order->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Lịch sử trạng thái (nếu có) -->
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Lịch Sử Trạng Thái</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="timeline">
                                            <div class="time-label">
                                                <span class="bg-red">{{ $order->created_at->format('d/m/Y') }}</span>
                                            </div>
                                            <div>
                                                <i class="fas fa-plus bg-blue"></i>
                                                <div class="timeline-item">
                                                    <span class="time"><i class="fas fa-clock"></i> {{ $order->created_at->format('H:i') }}</span>
                                                    <h3 class="timeline-header">Đơn hàng được tạo</h3>
                                                    <div class="timeline-body">
                                                        Đơn hàng #{{ $order->id }} được tạo với trạng thái "{{ $order->status }}"
                                                    </div>
                                                </div>
                                            </div>
                                            @if($order->updated_at != $order->created_at)
                                                <div>
                                                    <i class="fas fa-edit bg-yellow"></i>
                                                    <div class="timeline-item">
                                                        <span class="time"><i class="fas fa-clock"></i> {{ $order->updated_at->format('H:i') }}</span>
                                                        <h3 class="timeline-header">Đơn hàng được cập nhật</h3>
                                                        <div class="timeline-body">
                                                            Lần cập nhật gần nhất: {{ $order->updated_at->format('d/m/Y H:i') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div>
                                                <i class="fas fa-clock bg-gray"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal xác nhận xóa -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Xác Nhận Xóa</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa đơn hàng #{{ $order->id }}?</p>
                    <p class="text-danger"><strong>Lưu ý:</strong> Hành động này không thể hoàn tác!</p>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-danger">Xóa Đơn Hàng</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .card-tools, .btn, .modal, .sidebar, .navbar {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .card-header {
                background-color: transparent !important;
                border-bottom: 2px solid #000 !important;
            }
        }
    </style>
</x-admin-layout>
