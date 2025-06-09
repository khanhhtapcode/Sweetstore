<x-admin-layout>
    <x-slot name="header">
        Quản Lý Tài Xế 🚗
    </x-slot>

    <!-- Thông báo -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" id="success-alert">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" id="error-alert">
            ❌ {{ session('error') }}
        </div>
    @endif

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.drivers.create') }}"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <span class="mr-2">➕</span> Thêm Tài Xế
            </a>
            <a href="{{ route('admin.drivers.export') }}"
               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <span class="mr-2">📊</span> Xuất Excel
            </a>
        </div>
    </div>
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.drivers.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Tên, mã, SĐT, email..."
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tất cả</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>✅ Sẵn sàng</option>
                    <option value="busy" {{ request('status') == 'busy' ? 'selected' : '' }}>🚚 Đang bận</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>⏸️ Ngừng hoạt động</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Loại xe</label>
                <select name="vehicle_type" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tất cả</option>
                    <option value="motorbike" {{ request('vehicle_type') == 'motorbike' ? 'selected' : '' }}>🏍️ Xe máy</option>
                    <option value="small_truck" {{ request('vehicle_type') == 'small_truck' ? 'selected' : '' }}>🚚 Xe tải nhỏ</option>
                    <option value="van" {{ request('vehicle_type') == 'van' ? 'selected' : '' }}>🚐 Xe van</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bằng lái sắp hết hạn</label>
                <select name="license_expiring" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tất cả</option>
                    <option value="1" {{ request('license_expiring') ? 'selected' : '' }}>⚠️ Sắp hết hạn (30 ngày)</option>
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    🔍 Tìm
                </button>
                <a href="{{ route('admin.drivers.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    🔄 Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Drivers Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-2 sm:space-y-0">
                <h3 class="text-lg font-semibold text-gray-900">
                    Danh sách tài xế ({{ $drivers->total() }} tài xế)
                </h3>

                <!-- Sort Options -->
                <div class="flex space-x-2">
                    <select id="sortBy" onchange="updateSort()" class="border border-gray-300 rounded-md px-3 py-1 text-sm">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Ngày tạo</option>
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Tên</option>
                        <option value="total_deliveries" {{ request('sort_by') == 'total_deliveries' ? 'selected' : '' }}>Số đơn đã giao</option>
                        <option value="rating" {{ request('sort_by') == 'rating' ? 'selected' : '' }}>Đánh giá</option>
                    </select>

                    <select id="sortDirection" onchange="updateSort()" class="border border-gray-300 rounded-md px-3 py-1 text-sm">
                        <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>Giảm dần</option>
                        <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>Tăng dần</option>
                    </select>
                </div>
            </div>
        </div>

        @if($drivers->count() > 0)
            <!-- Bulk Actions -->
            <div class="px-6 py-3 bg-gray-50 border-b">
                <form id="bulkActionForm" action="{{ route('admin.drivers.bulk-action') }}" method="POST" class="flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                    @csrf
                    <input type="hidden" name="driver_ids" id="selectedDriverIds">

                    <label class="flex items-center">
                        <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600">Chọn tất cả</span>
                    </label>

                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                        <select name="action" class="border border-gray-300 rounded-md px-3 py-1 text-sm">
                            <option value="">-- Hành động --</option>
                            <option value="activate">✅ Kích hoạt</option>
                            <option value="deactivate">⏸️ Ngừng hoạt động</option>
                            <option value="delete">🗑️ Xóa</option>
                        </select>

                        <button type="button" onclick="executeBulkAction()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                            Thực hiện
                        </button>
                    </div>

                    <span id="selectedCount" class="text-sm text-gray-600">0 mục được chọn</span>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600" disabled>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tài xế
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Liên hệ
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Phương tiện
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Trạng thái
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Hiệu suất
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thao tác
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($drivers as $driver)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="driver-checkbox rounded border-gray-300 text-blue-600" value="{{ $driver->id }}">
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-sm font-medium text-blue-600">{{ substr($driver->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('admin.drivers.show', $driver) }}" class="text-blue-600 hover:text-blue-900">
                                                {{ $driver->name }}
                                            </a>
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $driver->driver_code }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $driver->phone }}</div>
                                <div class="text-sm text-gray-500">{{ $driver->email }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $driver->vehicle_type_name }}</div>
                                <div class="text-sm text-gray-500">{{ $driver->vehicle_number }}</div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusConfig = [
                                        'active' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Sẵn sàng'],
                                        'busy' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Đang bận'],
                                        'inactive' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Ngừng hoạt động']
                                    ];
                                    $config = $statusConfig[$driver->status] ?? $statusConfig['inactive'];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['class'] }}">
                                        {{ $config['text'] }}
                                    </span>

                                @if($driver->is_license_expired)
                                    <div class="mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                ❌ Bằng lái hết hạn
                                            </span>
                                    </div>
                                @elseif($driver->is_license_expiring_soon)
                                    <div class="mt-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                ⚠️ Sắp hết hạn
                                            </span>
                                    </div>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <div class="flex items-center space-x-2">
                                        <span>⭐ {{ $driver->formatted_rating }}</span>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <div class="flex items-center space-x-4">
                                        <span title="Tổng đơn hàng">📦 {{ $driver->orders_count ?? 0 }}</span>
                                        <span title="Đơn hiện tại">🚚 {{ $driver->current_orders_count ?? 0 }}</span>
                                        <span title="Đã hoàn thành">✅ {{ $driver->completed_orders_count ?? 0 }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.drivers.show', $driver) }}"
                                       class="text-blue-600 hover:text-blue-900"
                                       title="Xem chi tiết">
                                        👁️
                                    </a>

                                    <a href="{{ route('admin.drivers.edit', $driver) }}"
                                       class="text-green-600 hover:text-green-900"
                                       title="Chỉnh sửa">
                                        ✏️
                                    </a>

                                    <button onclick="toggleDriverStatus('{{ $driver->id }}', '{{ $driver->status === 'active' ? 'inactive' : 'active' }}')"
                                            class="text-yellow-600 hover:text-yellow-900"
                                            title="{{ $driver->status === 'active' ? 'Ngừng hoạt động' : 'Kích hoạt' }}">
                                        {{ $driver->status === 'active' ? '⏸️' : '▶️' }}
                                    </button>

                                    @if($driver->current_orders_count == 0)
                                        <button onclick="deleteDriver('{{ $driver->id }}', '{{ $driver->name }}')"
                                                class="text-red-600 hover:text-red-900"
                                                title="Xóa">
                                            🗑️
                                        </button>
                                    @else
                                        <span class="text-gray-400" title="Không thể xóa - đang có đơn hàng">🗑️</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $drivers->appends(request()->query())->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <div class="text-gray-500">
                    <div class="text-4xl mb-4">🚗</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Không có tài xế nào</h3>
                    <p class="text-gray-500 mb-4">
                        @if(request()->hasAny(['search', 'status', 'vehicle_type', 'license_expiring']))
                            Không tìm thấy tài xế nào với các điều kiện lọc hiện tại.
                            <a href="{{ route('admin.drivers.index') }}" class="text-blue-600 hover:text-blue-500">Xóa bộ lọc</a>
                        @else
                            Hãy thêm tài xế đầu tiên vào hệ thống.
                        @endif
                    </p>
                    @if(!request()->hasAny(['search', 'status', 'vehicle_type', 'license_expiring']))
                        <div class="mt-6">
                            <a href="{{ route('admin.drivers.create') }}"
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                ➕ Thêm Tài Xế Đầu Tiên
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <script>
        // Sort functionality
        function updateSort() {
            const sortBy = document.getElementById('sortBy').value;
            const sortDirection = document.getElementById('sortDirection').value;

            const url = new URL(window.location);
            url.searchParams.set('sort_by', sortBy);
            url.searchParams.set('sort_direction', sortDirection);

            window.location.href = url.toString();
        }

        // Checkbox functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.driver-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedCount();
        });

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('driver-checkbox')) {
                updateSelectedCount();

                // Update select all checkbox
                const checkboxes = document.querySelectorAll('.driver-checkbox');
                const checkedCheckboxes = document.querySelectorAll('.driver-checkbox:checked');
                const selectAllCheckbox = document.getElementById('selectAll');

                if (checkedCheckboxes.length === 0) {
                    selectAllCheckbox.indeterminate = false;
                    selectAllCheckbox.checked = false;
                } else if (checkedCheckboxes.length === checkboxes.length) {
                    selectAllCheckbox.indeterminate = false;
                    selectAllCheckbox.checked = true;
                } else {
                    selectAllCheckbox.indeterminate = true;
                }
            }
        });

        function updateSelectedCount() {
            const checkedCheckboxes = document.querySelectorAll('.driver-checkbox:checked');
            const count = checkedCheckboxes.length;
            document.getElementById('selectedCount').textContent = `${count} mục được chọn`;

            // Update hidden input with selected IDs
            const selectedIds = Array.from(checkedCheckboxes).map(cb => cb.value);
            document.getElementById('selectedDriverIds').value = selectedIds.join(',');
        }

        // Bulk actions
        function executeBulkAction() {
            const selectedIds = document.getElementById('selectedDriverIds').value;
            const action = document.querySelector('select[name="action"]').value;

            if (!selectedIds) {
                alert('❌ Vui lòng chọn ít nhất một tài xế!');
                return;
            }

            if (!action) {
                alert('❌ Vui lòng chọn hành động!');
                return;
            }

            const actionTexts = {
                'activate': 'kích hoạt',
                'deactivate': 'ngừng hoạt động',
                'delete': 'xóa'
            };

            const selectedCount = selectedIds.split(',').length;

            if (confirm(`Bạn có chắc chắn muốn ${actionTexts[action]} ${selectedCount} tài xế đã chọn?`)) {
                document.getElementById('bulkActionForm').submit();
            }
        }

        // Individual driver actions
        function toggleDriverStatus(driverId, newStatus) {
            const statusTexts = {
                'active': 'kích hoạt',
                'inactive': 'ngừng hoạt động'
            };

            if (confirm(`Bạn có chắc chắn muốn ${statusTexts[newStatus]} tài xế này?`)) {
                fetch(`/admin/drivers/${driverId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Lỗi: ' + data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi cập nhật trạng thái');
                    });
            }
        }

        function deleteDriver(driverId, driverName) {
            if (confirm(`❌ Bạn có chắc chắn muốn xóa tài xế "${driverName}"?\n\n⚠️ Hành động này không thể hoàn tác!`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/drivers/${driverId}`;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);

                document.body.appendChild(form);
                form.submit();
            }
        }

        // Auto dismiss alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('#success-alert, #error-alert');
            alerts.forEach(alert => {
                if (alert) {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            });
        }, 5000);

        // Real-time search
        let searchTimeout;
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (this.value.length >= 3 || this.value.length === 0) {
                        this.form.submit();
                    }
                }, 500);
            });
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Update count on page load
            updateSelectedCount();
        });
    </script>
</x-admin-layout>
