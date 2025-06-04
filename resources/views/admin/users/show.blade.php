<x-admin-layout>
    <x-slot name="header">
        Chi Tiết Người Dùng: {{ $user->name }}
    </x-slot>

    <div class="max-w-6xl mx-auto space-y-6">
        <!-- User Info Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900">Thông Tin Người Dùng</h2>
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Chỉnh Sửa
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Quay Lại
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column - User Info -->
                    <div class="space-y-6">
                        <!-- Avatar and Basic Info -->
                        <div class="flex items-center space-x-6">
                            <img class="h-24 w-24 rounded-full border-4 border-gray-200"
                                 src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF&size=96"
                                 alt="{{ $user->name }}">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h3>
                                <p class="text-gray-600 text-lg">{{ $user->email }}</p>
                                <div class="flex items-center mt-3 space-x-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        @if($user->role === 'admin')
                                            👑 {{ $user->role_name }}
                                        @else
                                            👤 {{ $user->role_name }}
                                        @endif
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <span class="w-2 h-2 mr-2 rounded-full {{ $user->is_active ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                        {{ $user->is_active ? 'Hoạt động' : 'Vô hiệu hóa' }}
                                    </span>
                                    @if($user->id === auth()->id())
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                            ⭐ Tài khoản của bạn
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Detailed Info -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Thông Tin Chi Tiết</h4>
                            <div class="grid grid-cols-1 gap-4">
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <span class="text-sm font-medium text-gray-500">ID Người dùng</span>
                                    <span class="text-sm font-semibold text-gray-900">#{{ $user->id }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <span class="text-sm font-medium text-gray-500">Ngày tham gia</span>
                                    <div class="text-right">
                                        <div class="text-sm font-semibold text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <span class="text-sm font-medium text-gray-500">Đăng nhập lần cuối</span>
                                    <div class="text-right">
                                        @if($user->last_login_at)
                                            <div class="text-sm font-semibold text-gray-900">{{ $user->last_login_at->format('d/m/Y H:i') }}</div>
                                            <div class="text-xs text-gray-500">{{ $user->last_login_at->diffForHumans() }}</div>
                                        @else
                                            <div class="text-sm font-semibold text-gray-500">Chưa có thông tin</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                    <span class="text-sm font-medium text-gray-500">Xác thực email</span>
                                    <div class="text-right">
                                        @if($user->email_verified_at)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                ✓ Đã xác thực
                                            </span>
                                            <div class="text-xs text-gray-500 mt-1">{{ $user->email_verified_at->format('d/m/Y') }}</div>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                ✗ Chưa xác thực
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm font-medium text-gray-500">Cập nhật lần cuối</span>
                                    <div class="text-right">
                                        <div class="text-sm font-semibold text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->updated_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Quick Stats -->
                    <div class="space-y-6">
                        <!-- Stats Grid -->
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Thống Kê</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Total Orders -->
                                <div class="bg-blue-50 rounded-lg p-4 text-center border border-blue-200">
                                    <div class="text-3xl font-bold text-blue-600 mb-2">
                                        {{ $user->orders()->count() }}
                                    </div>
                                    <div class="text-sm font-medium text-blue-800">Tổng đơn hàng</div>
                                    <div class="text-xs text-blue-600 mt-1">Tất cả đơn hàng</div>
                                </div>

                                <!-- Total Spent -->
                                <div class="bg-green-50 rounded-lg p-4 text-center border border-green-200">
                                    <div class="text-2xl font-bold text-green-600 mb-2">
                                        {{ number_format($user->orders()->sum('total_amount')) }}₫
                                    </div>
                                    <div class="text-sm font-medium text-green-800">Tổng chi tiêu</div>
                                    <div class="text-xs text-green-600 mt-1">Tổng giá trị</div>
                                </div>

                                <!-- Pending Orders -->
                                <div class="bg-yellow-50 rounded-lg p-4 text-center border border-yellow-200">
                                    <div class="text-3xl font-bold text-yellow-600 mb-2">
                                        {{ $user->orders()->where('status', 'pending')->count() }}
                                    </div>
                                    <div class="text-sm font-medium text-yellow-800">Đơn chờ xử lý</div>
                                    <div class="text-xs text-yellow-600 mt-1">Cần xử lý</div>
                                </div>

                                <!-- Account Age -->
                                <div class="bg-purple-50 rounded-lg p-4 text-center border border-purple-200">
                                    <div class="text-3xl font-bold text-purple-600 mb-2">
                                        {{ $user->created_at->diffInDays(now()) }}
                                    </div>
                                    <div class="text-sm font-medium text-purple-800">Ngày thành viên</div>
                                    <div class="text-xs text-purple-600 mt-1">Kể từ tham gia</div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Hành Động Nhanh</h4>
                            <div class="space-y-3">
                                @if($user->id !== auth()->id())
                                    <button onclick="toggleUserStatus({{ $user->id }})"
                                            class="w-full px-4 py-3 text-left text-sm font-medium rounded-lg transition-colors border
                                                {{ $user->is_active ? 'bg-red-50 text-red-700 hover:bg-red-100 border-red-200' : 'bg-green-50 text-green-700 hover:bg-green-100 border-green-200' }}">
                                        <div class="flex items-center">
                                            <span class="text-lg mr-3">{{ $user->is_active ? '🚫' : '✅' }}</span>
                                            <div>
                                                <div class="font-semibold">{{ $user->is_active ? 'Vô hiệu hóa tài khoản' : 'Kích hoạt tài khoản' }}</div>
                                                <div class="text-xs opacity-75">{{ $user->is_active ? 'Tạm khóa quyền truy cập' : 'Khôi phục quyền truy cập' }}</div>
                                            </div>
                                        </div>
                                    </button>

                                    <button onclick="changeUserRole({{ $user->id }}, '{{ $user->role === 'admin' ? 'user' : 'admin' }}')"
                                            class="w-full px-4 py-3 text-left text-sm font-medium rounded-lg bg-purple-50 text-purple-700 hover:bg-purple-100 transition-colors border border-purple-200">
                                        <div class="flex items-center">
                                            <span class="text-lg mr-3">{{ $user->role === 'admin' ? '👤' : '👑' }}</span>
                                            <div>
                                                <div class="font-semibold">{{ $user->role === 'admin' ? 'Chuyển thành Khách hàng' : 'Thăng cấp Admin' }}</div>
                                                <div class="text-xs opacity-75">{{ $user->role === 'admin' ? 'Gỡ quyền quản trị' : 'Cấp quyền quản trị' }}</div>
                                            </div>
                                        </div>
                                    </button>

                                    @if(!$user->email_verified_at)
                                        <button onclick="sendVerificationEmail({{ $user->id }})"
                                                class="w-full px-4 py-3 text-left text-sm font-medium rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors border border-blue-200">
                                            <div class="flex items-center">
                                                <span class="text-lg mr-3">📧</span>
                                                <div>
                                                    <div class="font-semibold">Gửi email xác thực</div>
                                                    <div class="text-xs opacity-75">Gửi lại email xác thực</div>
                                                </div>
                                            </div>
                                        </button>
                                    @endif

                                    <button onclick="resetPassword({{ $user->id }})"
                                            class="w-full px-4 py-3 text-left text-sm font-medium rounded-lg bg-orange-50 text-orange-700 hover:bg-orange-100 transition-colors border border-orange-200">
                                        <div class="flex items-center">
                                            <span class="text-lg mr-3">🔑</span>
                                            <div>
                                                <div class="font-semibold">Đặt lại mật khẩu</div>
                                                <div class="text-xs opacity-75">Gửi link reset password</div>
                                            </div>
                                        </div>
                                    </button>
                                @else
                                    <div class="text-center text-gray-500 py-6 border border-gray-200 rounded-lg bg-gray-50">
                                        <span class="text-2xl mb-2 block">👋</span>
                                        <p class="text-sm font-medium">Đây là tài khoản của bạn</p>
                                        <p class="text-xs opacity-75 mt-1">Không thể thực hiện hành động trên chính mình</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        @if($user->orders()->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Đơn Hàng Gần Đây</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $user->orders()->count() }} đơn hàng tổng cộng</p>
                        </div>
                        <a href="{{ route('admin.orders.index', ['user_id' => $user->id]) }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-200 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Xem tất cả đơn hàng
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Mã đơn hàng
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ngày đặt
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tổng tiền
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trạng thái
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($user->orders()->latest()->take(10)->get() as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $order->order_number }}</div>
                                    <div class="text-xs text-gray-500">#{{ $order->id }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                                    <div class="text-xs text-gray-500">{{ $order->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $order->formatted_total }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'confirmed' => 'bg-blue-100 text-blue-800',
                                                    'preparing' => 'bg-purple-100 text-purple-800',
                                                    'ready' => 'bg-green-100 text-green-800',
                                                    'delivered' => 'bg-green-100 text-green-800',
                                                    'cancelled' => 'bg-red-100 text-red-800'
                                                ];
                                            @endphp
                                            {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $order->status_name }}
                                        </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="text-blue-600 hover:text-blue-900 transition-colors">
                                        Xem chi tiết
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @if($user->orders()->count() > 10)
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 text-center">
                        <a href="{{ route('admin.orders.index', ['user_id' => $user->id]) }}"
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Xem thêm {{ $user->orders()->count() - 10 }} đơn hàng khác →
                        </a>
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Đơn Hàng</h3>
                </div>
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có đơn hàng nào</h3>
                    <p class="mt-1 text-sm text-gray-500">Người dùng này chưa thực hiện đơn hàng nào.</p>
                </div>
            </div>
        @endif
    </div>

    <script>
        function toggleUserStatus(userId) {
            if (!confirm('Bạn có chắc muốn thay đổi trạng thái người dùng này?')) return;

            fetch(`/admin/users/${userId}/toggle-active`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Có lỗi xảy ra');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra');
                });
        }

        function changeUserRole(userId, role) {
            const roleName = role === 'admin' ? 'Quản trị viên' : 'Khách hàng';
            if (!confirm(`Bạn có chắc muốn thay đổi vai trò người dùng này thành ${roleName}?`)) return;

            fetch(`/admin/users/${userId}/change-role`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ role: role })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Có lỗi xảy ra');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra');
                });
        }

        function sendVerificationEmail(userId) {
            if (!confirm('Gửi email xác thực cho người dùng này?')) return;

            fetch(`/admin/users/${userId}/send-verification`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Email xác thực đã được gửi!');
                    } else {
                        alert(data.message || 'Có lỗi xảy ra');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra');
                });
        }

        function resetPassword(userId) {
            if (!confirm('Gửi email đặt lại mật khẩu cho người dùng này?')) return;

            fetch(`/admin/users/${userId}/reset-password`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Email đặt lại mật khẩu đã được gửi!');
                    } else {
                        alert(data.message || 'Có lỗi xảy ra');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra');
                });
        }
    </script>
</x-admin-layout>
