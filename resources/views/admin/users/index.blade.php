<x-admin-layout>
    <x-slot name="header">
        Quản Lý Người Dùng
    </x-slot>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <!-- Header with Actions -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Danh Sách Người Dùng</h2>
                    <p class="text-sm text-gray-600 mt-1">Quản lý tài khoản và phân quyền người dùng</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-4">
                <!-- Search -->
                <div class="flex-1 min-w-64">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Tìm kiếm tên hoặc email..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Role Filter -->
                <div>
                    <select name="role" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tất cả vai trò</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Khách hàng</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tất cả trạng thái</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Vô hiệu hóa</option>
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <select name="sort" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                        <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>Email A-Z</option>
                    </select>
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Lọc
                    </button>

                    @if(request()->hasAny(['search', 'role', 'status', 'sort']))
                        <a href="{{ route('admin.users.index') }}"
                           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Xóa bộ lọc
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="overflow-x-auto">
            @if($users->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" class="rounded border-gray-300" id="select-all">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Người dùng
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Vai trò
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Trạng thái
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Số đơn hàng
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ngày tham gia
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thao tác
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="rounded border-gray-300 user-checkbox" value="{{ $user->id }}">
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full"
                                             src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF"
                                             alt="{{ $user->name }}">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $user->name }}
                                            @if($user->id === auth()->id())
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                        Bạn
                                                    </span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                        @if($user->role === 'admin')
                                            👑 {{ $user->role_name }}
                                        @else
                                            👤 {{ $user->role_name }}
                                        @endif
                                    </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <button onclick="toggleUserStatus({{ $user->id }})"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium cursor-pointer transition-colors
                                                {{ $user->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                    <span class="w-2 h-2 mr-1.5 rounded-full {{ $user->is_active ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                    {{ $user->is_active ? 'Hoạt động' : 'Vô hiệu hóa' }}
                                </button>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    {{ $user->orders_count ?? 0 }} đơn
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex flex-col">
                                    <span>{{ $user->created_at->format('d/m/Y') }}</span>
                                    <span class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="text-blue-600 hover:text-blue-900 transition-colors"
                                       title="Xem chi tiết">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>

                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="text-indigo-600 hover:text-indigo-900 transition-colors"
                                       title="Chỉnh sửa">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>

                                    <!-- Role Change Dropdown -->
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open"
                                                class="text-purple-600 hover:text-purple-900 transition-colors"
                                                title="Thay đổi vai trò">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                            </svg>
                                        </button>

                                        <div x-show="open"
                                             @click.away="open = false"
                                             class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                            <div class="py-1">
                                                <button onclick="changeUserRole({{ $user->id }}, 'admin')"
                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $user->role === 'admin' ? 'bg-purple-50 text-purple-700' : '' }}">
                                                    👑 Quản trị viên
                                                </button>
                                                <button onclick="changeUserRole({{ $user->id }}, 'user')"
                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $user->role === 'user' ? 'bg-blue-50 text-blue-700' : '' }}">
                                                    👤 Khách hàng
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    @if($user->id !== auth()->id())
                                        <button onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')"
                                                class="text-red-600 hover:text-red-900 transition-colors"
                                                title="Xóa người dùng">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Không tìm thấy người dùng nào</h3>
                    <p class="mt-1 text-sm text-gray-500">Thử thay đổi bộ lọc .</p>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Hiển thị {{ $users->firstItem() ?? 0 }} đến {{ $users->lastItem() ?? 0 }}
                        trong {{ $users->total() }} người dùng
                    </div>
                    <div>
                        {{ $users->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Bulk Actions (if users selected) -->
    <div id="bulk-actions" class="hidden fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-white rounded-lg shadow-lg border border-gray-200 px-6 py-4">
        <div class="flex items-center space-x-4">
            <span class="text-sm font-medium text-gray-700">
                <span id="selected-count">0</span> người dùng được chọn
            </span>

            <div class="flex space-x-2">
                <button onclick="bulkAction('activate')"
                        class="px-3 py-1 bg-green-100 text-green-700 rounded text-sm hover:bg-green-200">
                    Kích hoạt
                </button>
                <button onclick="bulkAction('deactivate')"
                        class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded text-sm hover:bg-yellow-200">
                    Vô hiệu hóa
                </button>
                <button onclick="bulkAction('delete')"
                        class="px-3 py-1 bg-red-100 text-red-700 rounded text-sm hover:bg-red-200">
                    Xóa
                </button>
            </div>

            <button onclick="clearSelection()"
                    class="text-gray-400 hover:text-gray-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <script>
        // Toggle user status
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

        // Change user role
        function changeUserRole(userId, role) {
            if (!confirm(`Bạn có chắc muốn thay đổi vai trò người dùng này thành ${role === 'admin' ? 'Quản trị viên' : 'Khách hàng'}?`)) return;

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

        // Delete user
        function deleteUser(userId, userName) {
            if (!confirm(`Bạn có chắc muốn xóa người dùng "${userName}"?\nHành động này không thể hoàn tác!`)) return;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/users/${userId}`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';

            form.appendChild(csrfToken);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }

        // Bulk selection functionality
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('select-all');
            const userCheckboxes = document.querySelectorAll('.user-checkbox');
            const bulkActions = document.getElementById('bulk-actions');
            const selectedCount = document.getElementById('selected-count');

            selectAll.addEventListener('change', function() {
                userCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActions();
            });

            userCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateBulkActions);
            });

            function updateBulkActions() {
                const selectedUsers = document.querySelectorAll('.user-checkbox:checked');
                const count = selectedUsers.length;

                selectedCount.textContent = count;

                if (count > 0) {
                    bulkActions.classList.remove('hidden');
                } else {
                    bulkActions.classList.add('hidden');
                }

                // Update select all checkbox
                selectAll.indeterminate = count > 0 && count < userCheckboxes.length;
                selectAll.checked = count === userCheckboxes.length;
            }
        });

        function clearSelection() {
            document.querySelectorAll('.user-checkbox:checked').forEach(checkbox => {
                checkbox.checked = false;
            });
            document.getElementById('select-all').checked = false;
            document.getElementById('bulk-actions').classList.add('hidden');
        }

        function bulkAction(action) {
            const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked')).map(cb => cb.value);

            if (selectedUsers.length === 0) {
                alert('Vui lòng chọn ít nhất một người dùng');
                return;
            }

            let confirmMessage = '';
            switch(action) {
                case 'activate':
                    confirmMessage = `Kích hoạt ${selectedUsers.length} người dùng đã chọn?`;
                    break;
                case 'deactivate':
                    confirmMessage = `Vô hiệu hóa ${selectedUsers.length} người dùng đã chọn?`;
                    break;
                case 'delete':
                    confirmMessage = `Xóa ${selectedUsers.length} người dùng đã chọn?\nHành động này không thể hoàn tác!`;
                    break;
            }

            if (!confirm(confirmMessage)) return;

            // Implement bulk action logic here
            console.log(`Bulk ${action} for users:`, selectedUsers);
            // You can implement the actual bulk action API call here
        }
    </script>
</x-admin-layout>
