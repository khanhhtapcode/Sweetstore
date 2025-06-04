{{-- resources/views/admin/orders/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Quản Lý Đơn Hàng')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh Sách Đơn Hàng</h3>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert">
                                    <span>&times;</span>
                                </button>
                            </div>
                        @endif

                        <!-- Filter Form -->
                        <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="text"
                                           name="search"
                                           class="form-control"
                                           placeholder="Tìm kiếm đơn hàng..."
                                           value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <select name="status" class="form-control">
                                        <option value="">-- Trạng thái --</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đã giao</option>
                                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Hoàn thành</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="payment_status" class="form-control">
                                        <option value="">-- Thanh toán --</option>
                                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                        <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Thất bại</option>
                                        <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="date"
                                           name="date_from"
                                           class="form-control"
                                           value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-2">
                                    <input type="date"
                                           name="date_to"
                                           class="form-control"
                                           value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-info">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-redo"></i>
                                    </a>
                                </div>
                            </div>
                        </form>

                        <!-- Orders Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th width="8%">Mã ĐH</th>
                                    <th width="15%">Khách hàng</th>
                                    <th width="12%">Ngày đặt</th>
                                    <th width="10%">Tổng tiền</th>
                                    <th width="10%">Trạng thái</th>
                                    <th width="10%">Thanh toán</th>
                                    <th width="10%">Giao hàng</th>
                                    <th width="15%">Ghi chú</th>
                                    <th width="10%">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>
                                            <strong>#{{ $order->order_number }}</strong>
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $order->customer_name }}</strong><br>
                                                <small class="text-muted">{{ $order->customer_email }}</small><br>
                                                <small class="text-muted">{{ $order->customer_phone }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <strong class="text-primary">{{ number_format($order->total_amount) }}₫</strong>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'processing' => 'info',
                                                    'shipped' => 'primary',
                                                    'delivered' => 'success',
                                                    'cancelled' => 'danger'
                                                ];
                                                $statusLabels = [
                                                    'pending' => 'Chờ xử lý',
                                                    'processing' => 'Đang xử lý',
                                                    'shipped' => 'Đã giao',
                                                    'delivered' => 'Hoàn thành',
                                                    'cancelled' => 'Đã hủy'
                                                ];
                                            @endphp
                                            <span class="badge badge-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                                {{ $statusLabels[$order->status] ?? $order->status }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $paymentColors = [
                                                    'pending' => 'warning',
                                                    'paid' => 'success',
                                                    'failed' => 'danger',
                                                    'refunded' => 'info'
                                                ];
                                                $paymentLabels = [
                                                    'pending' => 'Chờ thanh toán',
                                                    'paid' => 'Đã thanh toán',
                                                    'failed' => 'Thất bại',
                                                    'refunded' => 'Đã hoàn tiền'
                                                ];
                                            @endphp
                                            <span class="badge badge-{{ $paymentColors[$order->payment_status] ?? 'secondary' }}">
                                                {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>
                                                {{ $order->shipping_address }}<br>
                                                {{ $order->shipping_city }}, {{ $order->shipping_country }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($order->notes)
                                                <small class="text-muted">{{ Str::limit($order->notes, 50) }}</small>
                                            @else
                                                <small class="text-muted">Không có ghi chú</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                                   class="btn btn-sm btn-info"
                                                   title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.orders.edit', $order->id) }}"
                                                   class="btn btn-sm btn-warning"
                                                   title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($order->status !== 'cancelled')
                                                    <button type="button"
                                                            class="btn btn-sm btn-danger"
                                                            onclick="updateOrderStatus({{ $order->id }}, 'cancelled')"
                                                            title="Hủy đơn">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Không có đơn hàng nào.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($orders->hasPages())
                            <div class="d-flex justify-content-center">
                                {{ $orders->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cập Nhật Trạng Thái Đơn Hàng</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="statusForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="status">Trạng thái đơn hàng:</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="pending">Chờ xử lý</option>
                                <option value="processing">Đang xử lý</option>
                                <option value="shipped">Đã giao</option>
                                <option value="delivered">Hoàn thành</option>
                                <option value="cancelled">Đã hủy</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="notes">Ghi chú:</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Auto dismiss alerts
            $('.alert').delay(5000).fadeOut();
        });

        function updateOrderStatus(orderId, status) {
            $('#statusForm').attr('action', '/admin/orders/' + orderId + '/update-status');
            $('#status').val(status);
            $('#statusModal').modal('show');
        }
    </script>
@endsection
